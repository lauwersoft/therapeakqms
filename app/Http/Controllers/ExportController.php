<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\Browsershot\Browsershot;

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

        // Convert mermaid code blocks to renderable divs
        $html = preg_replace(
            '/<pre><code class="language-mermaid">(.*?)<\/code><\/pre>/s',
            '<div class="mermaid">$1</div>',
            $html
        );

        // Render the export template
        $exportHtml = view('documents.export-pdf', [
            'content' => $html,
            'meta' => $meta,
            'path' => $path,
        ])->render();

        // Generate PDF with Browsershot
        $filename = ($meta['id'] ?? 'document') . ' - ' . ($meta['title'] ?? basename($path, '.md')) . '.pdf';

        $pdfContent = Browsershot::html($exportHtml)
            ->setNodeBinary(trim(shell_exec('which node') ?: '/usr/bin/node'))
            ->setNpmBinary(trim(shell_exec('which npm') ?: '/usr/bin/npm'))
            ->setChromePath($this->findChrome())
            ->format('A4')
            ->margins(20, 15, 25, 15)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->pdf();

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function findChrome(): ?string
    {
        // Check for Puppeteer's bundled Chrome first
        $puppeteerChrome = base_path('node_modules/puppeteer/.local-chromium');
        if (is_dir($puppeteerChrome)) {
            $chromes = glob($puppeteerChrome . '/*/chrome-linux*/chrome');
            if (! empty($chromes)) {
                return $chromes[0];
            }
        }

        // Puppeteer v21+ stores Chrome differently
        $cacheDir = $_SERVER['HOME'] . '/.cache/puppeteer';
        if (is_dir($cacheDir)) {
            $chromes = glob($cacheDir . '/chrome/*/chrome-linux*/chrome');
            if (! empty($chromes)) {
                return end($chromes);
            }
        }

        // System Chrome/Chromium
        foreach (['/usr/bin/google-chrome', '/usr/bin/chromium-browser', '/usr/bin/chromium'] as $bin) {
            if (file_exists($bin)) {
                return $bin;
            }
        }

        return null;
    }
}
