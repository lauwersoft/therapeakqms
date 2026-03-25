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

    public function index(Request $request)
    {
        $records = [];

        if (is_dir($this->basePath)) {
            foreach (File::allFiles($this->basePath) as $file) {
                if (! str_ends_with($file->getFilename(), '.rec.json')) {
                    continue;
                }

                try {
                    $data = json_decode(File::get($file->getPathname()), true);
                    if (! is_array($data)) {
                        continue;
                    }
                } catch (\Throwable $e) {
                    continue;
                }

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

        // Sort newest first
        usort($records, function ($a, $b) {
            return strcmp($b['submitted_at'] ?? '', $a['submitted_at'] ?? '');
        });

        // Group by form
        $grouped = collect($records)->groupBy('form_id');

        return view('records.index', [
            'records' => $records,
            'grouped' => $grouped,
            'totalRecords' => count($records),
        ]);
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

        return view('records.show', [
            'record' => $data,
            'filename' => $filename,
        ]);
    }
}
