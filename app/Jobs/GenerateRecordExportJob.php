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

class GenerateRecordExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public function __construct(
        private int $exportId,
        private string $formId,
        private int $dateFilterDays = 0
    ) {}

    public function handle(): void
    {
        $export = DocumentExport::findOrFail($this->exportId);
        $export->update(['status' => 'processing']);

        $basePath = base_path('qms/records');
        $records = [];
        $cutoff = $this->dateFilterDays > 0
            ? now()->subDays($this->dateFilterDays)->toIso8601String()
            : null;

        if (is_dir($basePath)) {
            foreach (File::allFiles($basePath) as $file) {
                if (! str_ends_with($file->getFilename(), '.rec.json')) continue;
                $data = @json_decode(File::get($file->getPathname()), true);
                if (! is_array($data) || ($data['form_id'] ?? '') !== $this->formId) continue;
                if ($cutoff && ($data['submitted_at'] ?? '') < $cutoff) continue;
                $records[] = ['filename' => $file->getFilename(), 'data' => $data];
            }
        }

        if (empty($records)) {
            $export->update(['status' => 'failed', 'error' => 'No records found.']);
            return;
        }

        // Sort newest first
        usort($records, fn($a, $b) => ($b['data']['submitted_at'] ?? '') <=> ($a['data']['submitted_at'] ?? ''));

        $export->update(['total_docs' => count($records)]);

        $tmpDir = storage_path('app/qms-export/records-' . $export->id);
        @mkdir($tmpDir, 0755, true);

        $snapDir = '/home/sarp/snap/chromium/common/qms-export';
        $storageDir = storage_path('app/qms-export');

        $processed = 0;

        try {
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
                    copy($pdfFile, $tmpDir . '/' . $safeName);
                }

                @unlink($srcHtmlFile);
                @unlink($htmlFile);
                @unlink($pdfFile);

                $processed++;
                $export->update(['processed_docs' => $processed]);
            }

            // Find form title
            $formTitle = $records[0]['data']['form_title'] ?? $this->formId;
            $zipFilename = $this->formId . ' - ' . $formTitle . ' Records.zip';
            $zipPath = storage_path('app/qms-export/records-' . $export->id . '.zip');

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new \RuntimeException('Could not create ZIP.');
            }
            foreach (File::allFiles($tmpDir) as $file) {
                $zip->addFile($file->getPathname(), $file->getFilename());
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
