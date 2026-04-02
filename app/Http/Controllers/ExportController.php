<?php

namespace App\Http\Controllers;

use App\Services\DocumentMetadata;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

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

        // Convert mermaid blocks to rendered images via mermaid.ink
        $html = preg_replace_callback(
            '/<pre><code class="language-mermaid">(.*?)<\/code><\/pre>/s',
            function ($matches) {
                $mermaidCode = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5);
                // Strip any resolved [[DOC-ID]] links back to plain text for mermaid
                $mermaidCode = preg_replace('/<a [^>]*>([^<]*)<\/a>/', '$1', $mermaidCode);
                $mermaidCode = strip_tags($mermaidCode);

                // mermaid.ink uses base64url encoding
                $encoded = rtrim(strtr(base64_encode($mermaidCode), '+/', '-_'), '=');
                $imgUrl = 'https://mermaid.ink/img/' . $encoded . '?type=png&bgColor=white';

                // Download the image and embed as base64 data URI
                try {
                    $response = Http::timeout(60)->get($imgUrl);
                    if ($response->successful() && strlen($response->body()) > 100) {
                        $imageData = base64_encode($response->body());
                        return '<div style="text-align: center; margin: 16px 0;"><img src="data:image/png;base64,' . $imageData . '" style="max-width: 100%; height: auto;" /></div>';
                    }
                } catch (\Throwable $e) {
                    // Fall through to placeholder
                }

                return '<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin: 12px 0; font-size: 9px; color: #64748b; text-align: center;">[Diagram could not be rendered]</div>';
            },
            $html
        );

        // Render the export template
        $exportHtml = view('documents.export-pdf', [
            'content' => $html,
            'meta' => $meta,
            'path' => $path,
        ])->render();

        $filename = ($meta['id'] ?? 'document') . ' - ' . ($meta['title'] ?? basename($path, '.md')) . '.pdf';

        $pdf = Pdf::loadHTML($exportHtml)
            ->setPaper('a4')
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'sans-serif');

        return $pdf->download($filename);
    }
}
