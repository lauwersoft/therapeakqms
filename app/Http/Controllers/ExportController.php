<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateBulkExportJob;
use App\Models\DocumentExport;
use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\Process\Process;

class ExportController extends Controller
{
    public function recordPdf(Request $request, string $filename)
    {
        if (str_contains($filename, '..')) abort(403);

        $filePath = base_path('qms/records/' . $filename);
        if (! file_exists($filePath)) abort(404);

        $data = json_decode(File::get($filePath), true);
        if (! is_array($data)) abort(400);

        $exportHtml = view('records.export-pdf', [
            'record' => $data,
            'filename' => $filename,
        ])->render();

        $name = ($data['id'] ?? 'record') . ' - ' . ($data['title'] ?? $filename) . '.pdf';

        $uid = uniqid();
        $storageHtml = storage_path('app/qms-export');
        if (! is_dir($storageHtml)) @mkdir($storageHtml, 0755, true);

        $srcHtmlFile = $storageHtml . '/doc-' . $uid . '.html';
        $snapDir = '/home/sarp/snap/chromium/common/qms-export';
        $htmlFile = $snapDir . '/doc-' . $uid . '.html';
        $pdfFile = $snapDir . '/doc-' . $uid . '.pdf';

        file_put_contents($srcHtmlFile, $exportHtml);

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

        if (! file_exists($pdfFile)) {
            @unlink($srcHtmlFile);
            @unlink($htmlFile);
            abort(500, 'PDF generation failed.');
        }

        $pdfContent = file_get_contents($pdfFile);
        @unlink($srcHtmlFile);
        @unlink($htmlFile);
        @unlink($pdfFile);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $name . '"');
    }

    public function formPdf(Request $request, string $path)
    {
        $basePath = base_path('qms/documents');
        $filePath = realpath($basePath . '/' . $path);

        if (! $filePath || ! str_starts_with($filePath, realpath($basePath)) || ! file_exists($filePath)) {
            abort(404);
        }

        $json = json_decode(File::get($filePath), true);
        if (! $json) {
            abort(400, 'Invalid form file.');
        }

        $meta = array_merge(DocumentMetadata::DEFAULTS, array_intersect_key($json, DocumentMetadata::DEFAULTS));

        $exportHtml = view('documents.export-form-pdf', [
            'schema' => $json,
            'meta' => $meta,
            'path' => $path,
        ])->render();

        $filename = ($meta['id'] ?? 'form') . ' - ' . ($meta['title'] ?? basename($path, '.form.json')) . '.pdf';

        $uid = uniqid();
        $storageHtml = storage_path('app/qms-export');
        if (! is_dir($storageHtml)) {
            @mkdir($storageHtml, 0755, true);
        }
        $srcHtmlFile = $storageHtml . '/doc-' . $uid . '.html';
        $snapDir = '/home/sarp/snap/chromium/common/qms-export';
        $htmlFile = $snapDir . '/doc-' . $uid . '.html';
        $pdfFile = $snapDir . '/doc-' . $uid . '.pdf';

        file_put_contents($srcHtmlFile, $exportHtml);

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

        if (! file_exists($pdfFile)) {
            @unlink($srcHtmlFile);
            @unlink($htmlFile);
            abort(500, 'PDF generation failed.');
        }

        $pdfContent = file_get_contents($pdfFile);

        @unlink($srcHtmlFile);
        @unlink($htmlFile);
        @unlink($pdfFile);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

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

        // Render the export template
        $exportHtml = view('documents.export-pdf', [
            'content' => $html,
            'meta' => $meta,
            'path' => $path,
        ])->render();

        $filename = ($meta['id'] ?? 'document') . ' - ' . ($meta['title'] ?? basename($path, '.md')) . '.pdf';

        // Write HTML to storage first (www-data can write here)
        $uid = uniqid();
        $storageHtml = storage_path('app/qms-export');
        if (! is_dir($storageHtml)) {
            @mkdir($storageHtml, 0755, true);
        }
        $srcHtmlFile = $storageHtml . '/doc-' . $uid . '.html';
        file_put_contents($srcHtmlFile, $exportHtml);

        // Paths in sarp's snap directory (where snap chromium can read/write)
        $snapDir = '/home/sarp/snap/chromium/common/qms-export';
        $htmlFile = $snapDir . '/doc-' . $uid . '.html';
        $pdfFile = $snapDir . '/doc-' . $uid . '.pdf';

        // Copy HTML as sarp so snap chromium can read it, then run chromium
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

        if (! file_exists($pdfFile)) {
            @unlink($srcHtmlFile);
            @unlink($htmlFile);
            abort(500, 'PDF generation failed. Check server logs.');
        }

        $pdfContent = file_get_contents($pdfFile);

        @unlink($srcHtmlFile);
        @unlink($htmlFile);
        @unlink($pdfFile);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function xlsx(Request $request, string $path)
    {
        $basePath = base_path('qms/documents');
        $filePath = realpath($basePath . '/' . $path);

        if (! $filePath || ! str_starts_with($filePath, realpath($basePath)) || ! file_exists($filePath)) {
            abort(404);
        }

        if (! DocumentMetadata::isMarkdown($path)) {
            abort(400, 'Only markdown documents can be exported as XLSX.');
        }

        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);
        $meta = $parsed['meta'];
        $body = $parsed['body'];

        // Extract tables with their preceding headings
        $tables = $this->extractTablesFromMarkdown($body);

        if (empty($tables)) {
            abort(400, 'No tables found in this document.');
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($tables as $i => $table) {
            $sheet = $spreadsheet->createSheet();
            // Sheet names max 31 chars, no special chars
            $sheetName = substr(preg_replace('/[\\\\\/\?\*\[\]\:]/', '', $table['heading'] ?? 'Table ' . ($i + 1)), 0, 31);
            $sheet->setTitle($sheetName ?: 'Table ' . ($i + 1));

            foreach ($table['rows'] as $rowIdx => $row) {
                foreach ($row as $colIdx => $cell) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIdx + 1);
                    $cellRef = $sheet->getCell($colLetter . ($rowIdx + 1));
                    // Strip markdown formatting
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

            // Style header row
            if (count($table['rows']) > 0) {
                $lastCol = count($table['rows'][0]);
                $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol);

                // Header row styling
                $headerRange = 'A1:' . $lastColLetter . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '334155']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                ]);

                // Data rows styling
                $lastRow = count($table['rows']);
                if ($lastRow > 1) {
                    $dataRange = 'A2:' . $lastColLetter . $lastRow;
                    $sheet->getStyle($dataRange)->applyFromArray([
                        'font' => ['size' => 10],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
                    ]);
                }

                // Auto-size columns (with max width)
                for ($col = 1; $col <= $lastCol; $col++) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                }
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = ($meta['id'] ?? 'document') . ' - ' . ($meta['title'] ?? basename($path, '.md')) . '.xlsx';

        $tmpFile = storage_path('app/qms-export/xlsx-' . uniqid() . '.xlsx');
        if (! is_dir(dirname($tmpFile))) {
            @mkdir(dirname($tmpFile), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($tmpFile);

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Parse markdown body and extract all tables with their nearest preceding heading.
     */
    private function extractTablesFromMarkdown(string $markdown): array
    {
        $lines = explode("\n", $markdown);
        $tables = [];
        $currentHeading = null;
        $currentTable = null;

        foreach ($lines as $line) {
            // Track headings
            if (preg_match('/^#{1,4}\s+(.+)$/', $line, $m)) {
                $currentHeading = trim($m[1]);
                continue;
            }

            // Detect table rows (lines starting with |)
            if (preg_match('/^\|(.+)\|$/', trim($line))) {
                // Skip separator rows (|---|---|)
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
                // Non-table line — save current table if we have one
                if ($currentTable !== null && count($currentTable['rows']) > 1) {
                    $tables[] = $currentTable;
                }
                $currentTable = null;
            }
        }

        // Don't forget the last table
        if ($currentTable !== null && count($currentTable['rows']) > 1) {
            $tables[] = $currentTable;
        }

        return $tables;
    }

    /**
     * Count tables in a markdown document (used by the view to show/hide XLSX button).
     */
    public static function countTables(string $path): int
    {
        $filePath = base_path('qms/documents/' . $path);
        if (! file_exists($filePath) || ! DocumentMetadata::isMarkdown($path)) {
            return 0;
        }

        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);
        $lines = explode("\n", $parsed['body']);

        $tableCount = 0;
        $inTable = false;
        $rowCount = 0;

        foreach ($lines as $line) {
            if (preg_match('/^\|(.+)\|$/', trim($line))) {
                if (! preg_match('/^\|[\s\-\|:]+\|$/', trim($line))) {
                    if (! $inTable) {
                        $inTable = true;
                        $rowCount = 0;
                    }
                    $rowCount++;
                }
            } else {
                if ($inTable && $rowCount > 1) {
                    $tableCount++;
                }
                $inTable = false;
                $rowCount = 0;
            }
        }

        if ($inTable && $rowCount > 1) {
            $tableCount++;
        }

        return $tableCount;
    }

    public function formRecordsExport(Request $request, string $formId)
    {
        $dateFilter = $request->input('date_filter');

        $old = DocumentExport::where('user_id', $request->user()->id)->get();
        foreach ($old as $o) {
            if ($o->path && file_exists($o->path)) @unlink($o->path);
            $o->delete();
        }

        $export = DocumentExport::create([
            'user_id' => $request->user()->id,
            'category' => 'records:' . $formId . ($dateFilter ? ':' . $dateFilter : ''),
            'status' => 'pending',
        ]);

        \App\Jobs\GenerateRecordExportJob::dispatch($export->id, $formId, (int) $dateFilter);

        return response()->json(['id' => $export->id]);
    }

    public function recordExportStatus(DocumentExport $export)
    {
        return response()->json([
            'status' => $export->status,
            'total' => $export->total_docs,
            'processed' => $export->processed_docs,
            'error' => $export->error,
        ]);
    }

    public function allRecordsExport(Request $request)
    {
        $formIds = $request->input('form_ids', []);
        $dateFilter = (int) $request->input('date_filter', 0);

        $old = DocumentExport::where('user_id', $request->user()->id)->get();
        foreach ($old as $o) {
            if ($o->path && file_exists($o->path)) @unlink($o->path);
            $o->delete();
        }

        $export = DocumentExport::create([
            'user_id' => $request->user()->id,
            'category' => 'all-records',
            'status' => 'pending',
        ]);

        \App\Jobs\GenerateAllRecordsExportJob::dispatch($export->id, $formIds, $dateFilter);

        return response()->json(['id' => $export->id]);
    }

    public function allRecordsExportStatus(DocumentExport $export)
    {
        return response()->json([
            'status' => $export->status,
            'total' => $export->total_docs,
            'processed' => $export->processed_docs,
            'error' => $export->error,
        ]);
    }

    public function allRecordsExportDownload(DocumentExport $export)
    {
        if ($export->status !== 'ready' || ! $export->path || ! file_exists($export->path)) abort(404);
        $path = $export->path;
        $filename = $export->filename;
        $export->delete();
        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    public function recordExportDownload(DocumentExport $export)
    {
        if ($export->status !== 'ready' || ! $export->path || ! file_exists($export->path)) abort(404);

        $path = $export->path;
        $filename = $export->filename;
        $export->delete();

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    public function activeBulkExport(Request $request)
    {
        $export = DocumentExport::where('user_id', $request->user()->id)
            ->whereIn('status', ['pending', 'processing', 'ready'])
            ->latest()
            ->first();

        if (! $export) {
            return response()->json(['id' => null]);
        }

        return response()->json([
            'id' => $export->id,
            'status' => $export->status,
            'total' => $export->total_docs,
            'processed' => $export->processed_docs,
            'error' => $export->error,
        ]);
    }

    public function bulkExport(Request $request)
    {
        $category = $request->input('category');
        $includePdf = (bool) $request->input('include_pdf', true);
        $includeXlsx = (bool) $request->input('include_xlsx', true);

        // Delete any previous exports for this user
        $old = DocumentExport::where('user_id', $request->user()->id)->get();
        foreach ($old as $o) {
            if ($o->path && file_exists($o->path)) {
                @unlink($o->path);
            }
            $o->delete();
        }

        $export = DocumentExport::create([
            'user_id' => $request->user()->id,
            'category' => $category ?: null,
            'status' => 'pending',
        ]);

        GenerateBulkExportJob::dispatch($export->id, $includePdf, $includeXlsx);

        return response()->json(['id' => $export->id]);
    }

    public function bulkExportStatus(DocumentExport $export)
    {
        return response()->json([
            'status' => $export->status,
            'total' => $export->total_docs,
            'processed' => $export->processed_docs,
            'error' => $export->error,
        ]);
    }

    public function bulkExportDownload(DocumentExport $export)
    {
        if ($export->status !== 'ready' || ! $export->path || ! file_exists($export->path)) {
            abort(404);
        }

        $path = $export->path;
        $filename = $export->filename;

        // Delete export record and file after download
        $export->delete();

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
