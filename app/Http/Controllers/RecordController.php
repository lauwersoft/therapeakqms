<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RecordController extends Controller
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/records');
    }

    private function allRecords(): array
    {
        $records = [];
        if (is_dir($this->basePath)) {
            foreach (File::allFiles($this->basePath) as $file) {
                if (!str_ends_with($file->getFilename(), '.rec.json')) continue;
                try {
                    $data = json_decode(File::get($file->getPathname()), true);
                    if (!is_array($data)) continue;
                } catch (\Throwable $e) { continue; }

                $records[] = [
                    'filename' => $file->getFilename(),
                    'id' => $data['id'] ?? '',
                    'title' => $data['title'] ?? $file->getFilenameWithoutExtension(),
                    'form_id' => $data['form_id'] ?? '',
                    'form_title' => $data['form_title'] ?? '',
                    'author' => $data['author'] ?? '',
                    'status' => $data['status'] ?? 'submitted',
                    'submitted_at' => $data['submitted_at'] ?? null,
                ];
            }
        }
        return $records;
    }

    public function index(Request $request)
    {
        $records = $this->allRecords();
        $grouped = collect($records)->groupBy('form_id');

        // Build summary per form, sorted by form ID
        $forms = $grouped->map(function ($formRecords, $formId) {
            $latest = $formRecords->sortByDesc('submitted_at')->first();
            return [
                'form_id' => $formId,
                'form_title' => $formRecords->first()['form_title'] ?: $formId ?: 'Unknown',
                'count' => $formRecords->count(),
                'latest_at' => $latest['submitted_at'] ?? null,
                'latest_author' => $latest['author'] ?? '',
            ];
        })->sortBy('form_id')->values();

        return view('records.index', [
            'forms' => $forms,
            'totalRecords' => count($records),
        ]);
    }

    public function formRecords(Request $request, string $formId)
    {
        $records = collect($this->allRecords())
            ->where('form_id', $formId)
            ->sortByDesc('submitted_at')
            ->values();

        $formTitle = $records->first()['form_title'] ?? $formId;

        return view('records.form-records', [
            'formId' => $formId,
            'formTitle' => $formTitle,
            'records' => $records,
        ]);
    }

    public function destroy(Request $request, string $filename)
    {
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        if (str_contains($filename, '..')) {
            abort(403);
        }

        $filePath = $this->basePath . '/' . $filename;
        if (! File::exists($filePath)) {
            abort(404);
        }

        // Read record ID before deleting
        $data = @json_decode(File::get($filePath), true);
        $recId = $data['id'] ?? $filename;

        File::delete($filePath);

        // Auto-commit deletion
        app()->terminating(function () use ($recId) {
            $base = base_path();
            try {
                \Illuminate\Support\Facades\Process::path($base)->run('git add qms/records/');
                $diff = \Illuminate\Support\Facades\Process::path($base)->run('git diff --cached --quiet');
                if (! $diff->successful()) {
                    \Illuminate\Support\Facades\Process::path($base)->run(['git', 'commit', '--author', 'QMS System <qms@system>', '-m', "Deleted record {$recId}"]);
                    \Illuminate\Support\Facades\Process::path($base)->run('git push');
                }
            } catch (\Throwable $e) {
                // Silent
            }
        });

        return redirect()->route('records.index')->with('success', "Record {$recId} deleted.");
    }

    public function show(Request $request, string $filename)
    {
        if (str_contains($filename, '..')) {
            abort(403);
        }

        $filePath = $this->basePath . '/' . $filename;
        if (! File::exists($filePath)) {
            abort(404);
        }

        try {
            $data = json_decode(File::get($filePath), true);
        } catch (\Throwable $e) {
            abort(404);
        }

        if (! is_array($data)) {
            abort(404);
        }

        // Resolve form path from form_id if not set
        if (empty($data['form_path']) && !empty($data['form_id'])) {
            $docIndex = \App\Services\DocumentMetadata::index(base_path('qms/documents'));
            foreach ($docIndex as $docPath => $docMeta) {
                if (($docMeta['id'] ?? '') === $data['form_id']) {
                    $data['form_path'] = $docPath;
                    break;
                }
            }
        }

        return view('records.show', [
            'record' => $data,
            'filename' => $filename,
        ]);
    }
}
