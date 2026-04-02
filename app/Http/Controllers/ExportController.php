<?php

namespace App\Http\Controllers;

use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Symfony\Component\Process\Process;

class ExportController extends Controller
{
    public function pdf(Request $request, string $path)
    {
        $basePath = base_path('qms/documents');
        $filePath = realpath($basePath . '/' . $path);

        if (! $filePath || ! str_starts_with($filePath, realpath($basePath)) || ! file_exists($filePath)) {
            abort(404);
        }

        if (! DocumentMetadata::isMarkdown($path)) {
            abort(400, 'Only markdown documents can be exported as PDF.');
        }

        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);
        $meta = $parsed['meta'];

        // Render markdown to HTML
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($parsed['body'])->getContent();

        // Extract mermaid blocks BEFORE resolving links
        $mermaidBlocks = [];
        $html = preg_replace_callback(
            '/<pre><code class="language-mermaid">(.*?)<\/code><\/pre>/s',
            function ($matches) use (&$mermaidBlocks) {
                $placeholder = '<!--MERMAID_' . count($mermaidBlocks) . '-->';
                $mermaidCode = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5);
                $mermaidBlocks[] = $mermaidCode;
                return $placeholder;
            },
            $html
        );

        // Resolve cross-references and regulatory links
        $docIndex = DocumentMetadata::index($basePath);
        $idMap = DocumentMetadata::idMap($docIndex);
        $html = DocumentMetadata::resolveLinks($html, $idMap);
        $html = DocumentMetadata::resolveRegulatoryLinks($html);

        // Add IDs to headings
        $html = preg_replace_callback('/<(h[123])>(.*?)<\/\1>/s', function ($m) {
            $tag = $m[1];
            $text = strip_tags($m[2]);
            $id = \Illuminate\Support\Str::slug($text);
            return '<' . $tag . ' id="' . $id . '">' . $m[2] . '</' . $tag . '>';
        }, $html);

        // Render mermaid blocks to images
        foreach ($mermaidBlocks as $i => $mermaidCode) {
            $cleanCode = str_replace('\n', '<br/>', $mermaidCode);
            $wrappedCode = "%%{init: {'theme': 'neutral', 'themeVariables': {'fontSize': '12px'}}}%%\n" . $cleanCode;
            $encoded = rtrim(strtr(base64_encode($wrappedCode), '+/', '-_'), '=');
            $imgUrl = 'https://mermaid.ink/img/' . $encoded . '?type=png&bgColor=white&width=700';

            $replacement = '<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin: 12px 0; font-size: 9px; color: #64748b; text-align: center;">[Diagram could not be rendered]</div>';

            try {
                $response = Http::timeout(60)->get($imgUrl);
                if ($response->successful() && strlen($response->body()) > 100) {
                    $imageData = base64_encode($response->body());
                    $replacement = '<div style="text-align: center; margin: 16px 0; page-break-inside: avoid;"><img src="data:image/png;base64,' . $imageData . '" style="max-width: 100%; max-height: 700px; height: auto;" /></div>';
                }
            } catch (\Throwable $e) {
                // Keep placeholder
            }

            $html = str_replace('<!--MERMAID_' . $i . '-->', $replacement, $html);
        }

        // Render the export template
        $exportHtml = view('documents.export-pdf', [
            'content' => $html,
            'meta' => $meta,
            'path' => $path,
        ])->render();

        $filename = ($meta['id'] ?? 'document') . ' - ' . ($meta['title'] ?? basename($path, '.md')) . '.pdf';

        // /var/www/.qms-export — writable by www-data and accessible by snap chromium
        $tmpDir = '/var/www/.qms-export';
        if (! is_dir($tmpDir)) {
            @mkdir($tmpDir, 0777, true);
        }
        $uid = uniqid();
        $htmlFile = $tmpDir . '/doc-' . $uid . '.html';
        $pdfFile = $tmpDir . '/doc-' . $uid . '.pdf';
        file_put_contents($htmlFile, $exportHtml);
        chmod($htmlFile, 0666);

        // Run chromium as sarp user (snap requires a real user, not www-data)
        $process = new Process([
            'sudo', '-u', 'sarp',
            'snap', 'run', 'chromium',
            '--headless',
            '--no-sandbox',
            '--disable-gpu',
            '--disable-software-rasterizer',
            '--print-to-pdf=' . $pdfFile,
            '--no-pdf-header-footer',
            'file://' . $htmlFile,
        ]);
        $process->setTimeout(120);

        $process->run();

        if (! file_exists($pdfFile)) {
            $error = $process->getErrorOutput();
            @unlink($htmlFile);
            abort(500, 'PDF generation failed: ' . substr($error, 0, 500));
        }

        $pdfContent = file_get_contents($pdfFile);

        @unlink($htmlFile);
        @unlink($pdfFile);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
