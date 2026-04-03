<?php

namespace App\Jobs;

use App\Models\DocumentExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use ZipArchive;

class GenerateAllRecordsExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public function __construct(
        private int $exportId,
        private array $formIds,
        private int $dateFilterDays = 0
    ) {}

    public function handle(): void
    {
        $export = DocumentExport::findOrFail($this->exportId);
        $export->update(['status' => 'processing']);

        $basePath = base_path('qms/records');
        $cutoff = $this->dateFilterDays > 0
            ? now()->subDays($this->dateFilterDays)->toIso8601String()
            : null;

        // Collect all matching records grouped by form
        $grouped = [];

        if (is_dir($basePath)) {
            foreach (File::allFiles($basePath) as $file) {
                if (! str_ends_with($file->getFilename(), '.rec.json')) continue;
                $data = @json_decode(File::get($file->getPathname()), true);
                if (! is_array($data)) continue;

                $formId = $data['form_id'] ?? '';
                if (! in_array($formId, $this->formIds)) continue;
                if ($cutoff && ($data['submitted_at'] ?? '') < $cutoff) continue;

                $grouped[$formId][] = ['filename' => $file->getFilename(), 'data' => $data];
            }
        }

        $totalRecords = array_sum(array_map('count', $grouped));
        if ($totalRecords === 0) {
            $export->update(['status' => 'failed', 'error' => 'No records found matching the filters.']);
            return;
        }

        $export->update(['total_docs' => $totalRecords]);

        $tmpDir = storage_path('app/qms-export/allrecords-' . $export->id);
        @mkdir($tmpDir, 0755, true);

        $snapDir = '/home/sarp/snap/chromium/common/qms-export';
        $storageDir = storage_path('app/qms-export');

        $processed = 0;

        // Resolve form titles
        $docIndex = \App\Services\DocumentMetadata::index(base_path('qms/documents'));
        $formTitles = [];
        foreach ($docIndex as $docPath => $docMeta) {
            if (! empty($docMeta['id'])) $formTitles[$docMeta['id']] = $docMeta['title'] ?? $docMeta['id'];
        }

        try {
            foreach ($grouped as $formId => $records) {
                // Sort newest first
                usort($records, fn($a, $b) => ($b['data']['submitted_at'] ?? '') <=> ($a['data']['submitted_at'] ?? ''));

                $formTitle = $formTitles[$formId] ?? $records[0]['data']['form_title'] ?? $formId;
                $dirName = preg_replace('/[\/\\\\:*?"<>|]/', '', $formId . ' - ' . $formTitle);
                $outDir = $tmpDir . '/' . $dirName;
                @mkdir($outDir, 0755, true);

                foreach ($records as $rec) {
                    $data = $rec['data'];
                    $id = $data['id'] ?? pathinfo($rec['filename'], PATHINFO_FILENAME);
                    $title = $data['title'] ?? $id;
                    $safeName = preg_replace('/[\/\\\\:*?"<>|]/', '', $id . ' - ' . $title) . '.pdf';

                    $exportHtml = view('records.export-pdf', [
                        'record' => $data,
                        'filename' => $rec['filename'],
                    ])->render();

                    $uid = uniqid();
                    $srcHtmlFile = $storageDir . '/rec-' . $uid . '.html';
                    $htmlFile = $snapDir . '/rec-' . $uid . '.html';
                    $pdfFile = $snapDir . '/rec-' . $uid . '.pdf';

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

                    if (file_exists($pdfFile)) {
                        copy($pdfFile, $outDir . '/' . $safeName);
                    }

                    @unlink($srcHtmlFile);
                    @unlink($htmlFile);
                    @unlink($pdfFile);

                    $processed++;
                    $export->update(['processed_docs' => $processed]);
                }
            }

            $zipFilename = 'QMS Records - Therapeak B.V.zip';
            $zipPath = storage_path('app/qms-export/allrecords-' . $export->id . '.zip');

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create ZIP.');
            }

            foreach (File::allFiles($tmpDir) as $file) {
                $zip->addFile($file->getPathname(), $file->getRelativePathname());
            }
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
            File::deleteDirectory($tmpDir);
        }
    }
}
