<?php

namespace App\Jobs;

use App\Models\DocumentExport;
use App\Services\DocumentMetadata;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Process\Process;
use ZipArchive;

class GenerateBulkExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public function __construct(
        private int $exportId
    ) {}

    public function handle(): void
    {
        $export = DocumentExport::findOrFail($this->exportId);
        $export->update(['status' => 'processing']);

        $basePath = base_path('qms/documents');
        $docIndex = DocumentMetadata::index($basePath);
        $idMap = DocumentMetadata::idMap($docIndex);

        // Filter documents by category if set
        $docs = [];
        foreach ($docIndex as $path => $meta) {
            if (! DocumentMetadata::isMarkdown($path)) {
                continue;
            }
            if ($export->category) {
                $cats = DocumentMetadata::normalizeCategory($meta['category'] ?? []);
                if (! in_array($export->category, $cats)) {
                    continue;
                }
            }
            $docs[$path] = $meta;
        }

        $export->update(['total_docs' => count($docs)]);

        // Build a map of doc ID → relative PDF path for cross-linking
        $pdfPathMap = [];
        foreach ($docs as $path => $meta) {
            $dir = dirname($path);
            $id = $meta['id'] ?? pathinfo($path, PATHINFO_FILENAME);
            $title = $meta['title'] ?? pathinfo($path, PATHINFO_FILENAME);
            $safeName = preg_replace('/[\/\\\\:*?"<>|]/', '', $id . ' - ' . $title);
            $pdfPathMap[$meta['id'] ?? ''] = $dir . '/' . $safeName . '.pdf';
        }

        // Create temp directory
        $tmpDir = storage_path('app/qms-export/bulk-' . $export->id);
        @mkdir($tmpDir, 0755, true);

        $processed = 0;

        try {
            foreach ($docs as $path => $meta) {
                $this->generateDocumentFiles($basePath, $path, $meta, $docIndex, $idMap, $pdfPathMap, $tmpDir);
                $processed++;
                $export->update(['processed_docs' => $processed]);
            }

            // Create ZIP
            $zipFilename = ($export->category ? ucfirst($export->category) . ' Documentation' : 'QMS Documentation') . ' - Therapeak B.V.zip';
            $zipPath = storage_path('app/qms-export/' . $export->id . '.zip');

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create ZIP file');
            }

            $this->addDirectoryToZip($zip, $tmpDir, '');
            $zip->close();

            $export->update([
                'status' => 'ready',
                'filename' => $zipFilename,
                'path' => $zipPath,
            ]);
        } catch (\Throwable $e) {
            $export->update([
                'status' => 'failed',
                'error' => substr($e->getMessage(), 0, 500),
            ]);
        } finally {
            // Clean up temp directory
            File::deleteDirectory($tmpDir);
        }
    }

    private function generateDocumentFiles(
        string $basePath,
        string $path,
        array $meta,
        array $docIndex,
        array $idMap,
        array $pdfPathMap,
        string $tmpDir
    ): void {
        $filePath = $basePath . '/' . $path;
        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);
        $body = $parsed['body'];

        // Set up markdown converter
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());
        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($body)->getContent();

        // Extract mermaid blocks before resolving links
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

        // Resolve links — but rewrite cross-references to point to relative PDF paths
        $html = $this->resolveLinksForPdf($html, $idMap, $pdfPathMap, $path);
        $html = DocumentMetadata::resolveRegulatoryLinks($html);

        // Add IDs to headings
        $html = preg_replace_callback('/<(h[123])>(.*?)<\/\1>/s', function ($m) {
            $tag = $m[1];
            $text = strip_tags($m[2]);
            $id = \Illuminate\Support\Str::slug($text);
            return '<' . $tag . ' id="' . $id . '">' . $m[2] . '</' . $tag . '>';
        }, $html);

        // Render mermaid blocks
        foreach ($mermaidBlocks as $i => $mermaidCode) {
            $cleanCode = str_replace('\n', '<br/>', $mermaidCode);
            $cleanCode = str_replace(['[[', ']]'], '', $cleanCode);
            $wrappedCode = "%%{init: {'theme': 'neutral', 'themeVariables': {'fontSize': '12px'}}}%%\n" . $cleanCode;
            $encoded = rtrim(strtr(base64_encode($wrappedCode), '+/', '-_'), '=');
            $imgUrl = 'https://mermaid.ink/img/' . $encoded . '?type=png&bgColor=white&width=2000';

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

        // Build output paths
        $dir = dirname($path);
        $id = $meta['id'] ?? pathinfo($path, PATHINFO_FILENAME);
        $title = $meta['title'] ?? pathinfo($path, PATHINFO_FILENAME);
        $safeName = preg_replace('/[\/\\\\:*?"<>|]/', '', $id . ' - ' . $title);
        $outDir = $tmpDir . '/' . $dir;
        @mkdir($outDir, 0755, true);

        // Generate PDF
        $exportHtml = view('documents.export-pdf', [
            'content' => $html,
            'meta' => $meta,
            'path' => $path,
        ])->render();

        $pdfPath = $outDir . '/' . $safeName . '.pdf';
        $this->generatePdf($exportHtml, $pdfPath);

        // Rewrite dummy URLs to relative paths
        $this->rewritePdfLinks($pdfPath, $dir);

        // Generate XLSX if document has tables
        $tables = $this->extractTablesFromMarkdown($body);
        if (! empty($tables)) {
            $this->generateXlsx($tables, $outDir . '/' . $safeName . '.xlsx');
        }
    }

    /**
     * Resolve [[DOC-ID]] links to relative PDF paths instead of web URLs.
     */
    private function resolveLinksForPdf(string $html, array $idMap, array $pdfPathMap, string $currentPath): string
    {
        $dummyBase = 'http://QMSLINK/';

        // Count how many [[DOC-ID]] patterns exist in the HTML
        $matchCount = preg_match_all('/\[\[([A-Z]+-\d{3,})\]\]/', $html);
        \Illuminate\Support\Facades\Log::info('resolveLinksForPdf: ' . basename($currentPath) . ' | found ' . $matchCount . ' [[DOC-ID]] patterns | pdfPathMap has ' . count($pdfPathMap) . ' entries');

        return preg_replace_callback('/\[\[([A-Z]+-\d{3,})\]\]/', function ($matches) use ($pdfPathMap, $dummyBase) {
            $docId = $matches[1];
            if (isset($pdfPathMap[$docId])) {
                $targetPath = $pdfPathMap[$docId];
                $fullUrl = $dummyBase . $targetPath;
                return '<a href="' . htmlspecialchars($fullUrl) . '" style="color: #2563eb; font-weight: 500; text-decoration: none;">'
                    . htmlspecialchars($docId) . '</a>';
            }
            return '<span style="color: #2563eb; font-weight: 500;">' . htmlspecialchars($docId) . '</span>';
        }, $html);
    }

    /**
     * Rewrite dummy QMSLINK URLs in the PDF to relative paths.
     */
    private function rewritePdfLinks(string $pdfPath, string $pdfFolder): void
    {
        $content = file_get_contents($pdfPath);
        if ($content === false) {
            return;
        }

        $dummyBase = 'http://QMSLINK/';

        // First decompress the PDF so we can find the URL strings
        $decompressed = $pdfPath . '.qdf';
        $process = new Process(['qpdf', '--qdf', '--object-streams=disable', $pdfPath, $decompressed]);
        $process->run();

        if (! file_exists($decompressed)) {
            \Illuminate\Support\Facades\Log::info('rewritePdfLinks: qpdf decompress failed for ' . $pdfPath . ' | ' . $process->getErrorOutput());
            return;
        }

        $content = file_get_contents($decompressed);
        @unlink($decompressed);

        $found = strpos($content, 'QMSLINK') !== false;
        // Also check for hex-encoded version
        $hexFound = strpos($content, '514d534c494e4b') !== false; // QMSLINK in hex
        // Find any URI annotations to see what format they use
        preg_match_all('/\/URI\s*.{0,100}/s', $content, $uriMatches);
        $uriSample = array_slice($uriMatches[0] ?? [], 0, 3);
        \Illuminate\Support\Facades\Log::info('rewritePdfLinks: decompressed ' . basename($pdfPath) . ' | QMSLINK found: ' . ($found ? 'YES' : 'NO') . ' | hex: ' . ($hexFound ? 'YES' : 'NO') . ' | URI samples: ' . json_encode($uriSample));

        if (! $found) {
            return;
        }

        $pattern = '/\/URI\s*\((' . preg_quote($dummyBase, '/') . '[^)]*)\)/';

        $content = preg_replace_callback($pattern, function (array $matches) use ($dummyBase, $pdfFolder) {
            $fullUrl = $matches[1];
            $relativePath = urldecode(substr($fullUrl, strlen($dummyBase)));

            if ($pdfFolder !== '') {
                $depth = substr_count(trim($pdfFolder, '/'), '/') + 1;
                $prefix = str_repeat('../', $depth);
                $relativePath = $prefix . $relativePath;
            }

            $escaped = str_replace(
                ['\\', '(', ')'],
                ['\\\\', '\\(', '\\)'],
                $relativePath
            );

            return '/URI (' . $escaped . ')';
        }, $content);

        // Write modified content and re-linearize
        $modifiedFile = $pdfPath . '.mod';
        file_put_contents($modifiedFile, $content);

        $process = new Process(['qpdf', '--linearize', $modifiedFile, $pdfPath . '.final']);
        $process->run();

        if (file_exists($pdfPath . '.final')) {
            rename($pdfPath . '.final', $pdfPath);
        } else {
            // Fallback: use the modified file directly
            rename($modifiedFile, $pdfPath);
        }

        @unlink($modifiedFile);
        @unlink($pdfPath . '.final');
    }

    /**
     * Calculate relative path from one directory to a target file.
     */
    private function relativePath(string $fromDir, string $toPath): string
    {
        $fromParts = $fromDir ? explode('/', $fromDir) : [];
        $toParts = explode('/', $toPath);

        // Find common prefix
        $common = 0;
        while ($common < count($fromParts) && $common < count($toParts) - 1
            && $fromParts[$common] === $toParts[$common]) {
            $common++;
        }

        $ups = count($fromParts) - $common;
        $remaining = array_slice($toParts, $common);

        return str_repeat('../', $ups) . implode('/', $remaining);
    }

    private function generatePdf(string $html, string $outputPath): void
    {
        $snapDir = '/home/sarp/snap/chromium/common/qms-export';
        $uid = uniqid();
        $storageDir = storage_path('app/qms-export');

        $srcHtmlFile = $storageDir . '/bulk-' . $uid . '.html';
        $htmlFile = $snapDir . '/bulk-' . $uid . '.html';
        $pdfFile = $snapDir . '/bulk-' . $uid . '.pdf';

        file_put_contents($srcHtmlFile, $html);

        $process = new Process([
            'sudo', '-u', 'sarp', 'bash', '-c',
            'cp ' . escapeshellarg($srcHtmlFile) . ' ' . escapeshellarg($htmlFile) .
            ' && snap run chromium --headless --no-sandbox --disable-gpu --disable-software-rasterizer' .
            ' --print-to-pdf=' . escapeshellarg($pdfFile) .
            ' --no-pdf-header-footer' .
            ' file://' . escapeshellarg($htmlFile),
        ]);
        $process->setTimeout(120);
        $process->run();

        if (file_exists($pdfFile)) {
            copy($pdfFile, $outputPath);
        }

        @unlink($srcHtmlFile);
        @unlink($htmlFile);
        @unlink($pdfFile);
    }

    private function generateXlsx(array $tables, string $outputPath): void
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($tables as $i => $table) {
            $sheet = $spreadsheet->createSheet();
            $sheetName = substr(preg_replace('/[\\\\\/\?\*\[\]\:]/', '', $table['heading'] ?? 'Table ' . ($i + 1)), 0, 31);
            $sheet->setTitle($sheetName ?: 'Table ' . ($i + 1));

            foreach ($table['rows'] as $rowIdx => $row) {
                foreach ($row as $colIdx => $cell) {
                    $colLetter = Coordinate::stringFromColumnIndex($colIdx + 1);
                    $cellRef = $sheet->getCell($colLetter . ($rowIdx + 1));
                    $cleanCell = $cell;
                    $cleanCell = preg_replace('/\[\[([A-Z]+-\d+)\]\]/', '$1', $cleanCell);
                    $cleanCell = preg_replace('/\*\*(.+?)\*\*/', '$1', $cleanCell);
                    $cleanCell = preg_replace('/\*(.+?)\*/', '$1', $cleanCell);
                    $cleanCell = preg_replace('/`(.+?)`/', '$1', $cleanCell);
                    $cleanCell = preg_replace('/\[([^\]]+)\]\([^\)]+\)/', '$1', $cleanCell);
                    $cleanCell = strip_tags(html_entity_decode($cleanCell));
                    $cellRef->setValue(trim($cleanCell));
                }
            }

            if (count($table['rows']) > 0) {
                $lastCol = count($table['rows'][0]);
                $lastColLetter = Coordinate::stringFromColumnIndex($lastCol);

                $sheet->getStyle('A1:' . $lastColLetter . '1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '334155']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                ]);

                $lastRow = count($table['rows']);
                if ($lastRow > 1) {
                    $sheet->getStyle('A2:' . $lastColLetter . $lastRow)->applyFromArray([
                        'font' => ['size' => 10],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                    ]);
                }

                for ($col = 1; $col <= $lastCol; $col++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setAutoSize(true);
                }
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $writer->save($outputPath);
    }

    private function extractTablesFromMarkdown(string $markdown): array
    {
        $lines = explode("\n", $markdown);
        $tables = [];
        $currentHeading = null;
        $currentTable = null;

        foreach ($lines as $line) {
            if (preg_match('/^#{1,4}\s+(.+)$/', $line, $m)) {
                $currentHeading = trim($m[1]);
                continue;
            }

            if (preg_match('/^\|(.+)\|$/', trim($line))) {
                if (preg_match('/^\|[\s\-\|:]+\|$/', trim($line))) {
                    continue;
                }

                $cells = array_map('trim', explode('|', trim($line, '|')));

                if ($currentTable === null) {
                    $currentTable = [
                        'heading' => $currentHeading,
                        'rows' => [],
                    ];
                }

                $currentTable['rows'][] = $cells;
            } else {
                if ($currentTable !== null && count($currentTable['rows']) > 1) {
                    $tables[] = $currentTable;
                }
                $currentTable = null;
            }
        }

        if ($currentTable !== null && count($currentTable['rows']) > 1) {
            $tables[] = $currentTable;
        }

        return $tables;
    }

    private function addDirectoryToZip(ZipArchive $zip, string $dir, string $prefix): void
    {
        foreach (File::allFiles($dir) as $file) {
            $relativePath = $prefix ? $prefix . '/' . $file->getRelativePathname() : $file->getRelativePathname();
            $zip->addFile($file->getPathname(), $relativePath);
        }
    }
}
