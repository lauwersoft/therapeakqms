<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FormController extends Controller
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/documents');
    }

    /**
     * Safely read and parse a form JSON file. Returns null if invalid.
     */
    private function readForm(string $fullPath): ?array
    {
        if (! File::exists($fullPath)) return null;
        if (! str_starts_with(realpath($fullPath), realpath($this->basePath))) return null;

        $raw = File::get($fullPath);
        $data = json_decode($raw, true);

        if (! is_array($data) || empty($data['fields'])) return null;

        return $data;
    }

    /**
     * Extract metadata from form data.
     */
    private function formMeta(array $data): array
    {
        return array_merge(DocumentMetadata::DEFAULTS, array_intersect_key($data, DocumentMetadata::DEFAULTS));
    }

    /**
     * Fill in a form (new submission).
     */
    public function fill(Request $request, string $path)
    {
        if (! str_ends_with($path, '.form.json')) {
            $path .= '.form.json';
        }

        $fullPath = $this->basePath . '/' . $path;
        $schema = $this->readForm($fullPath);

        if (! $schema) {
            abort(404, 'Form not found or invalid.');
        }

        return view('forms.fill', [
            'schema' => $schema,
            'meta' => $this->formMeta($schema),
            'path' => $path,
        ]);
    }

    /**
     * Save a form submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'form_path' => 'required|string',
            'title' => 'required|string|max:255',
            'fields' => 'required|array',
        ]);

        $path = $request->input('form_path');
        $fullPath = $this->basePath . '/' . $path;
        $schema = $this->readForm($fullPath);

        if (! $schema) {
            return back()->withErrors(['form_path' => 'Form template not found or invalid.']);
        }

        $formId = $schema['id'] ?? basename($path);
        $user = $request->user();

        // Save as file in qms/records/
        $recordsBase = base_path('qms/records');
        $recId = DocumentMetadata::nextId('REC', $recordsBase);
        $recordData = [
            'id' => $recId,
            'title' => $request->input('title'),
            'type' => 'REC',
            'version' => '1.0',
            'status' => 'submitted',
            'author' => $user->name,
            'form_id' => $formId,
            'form_path' => $path,
            'form_title' => $schema['title'] ?? '',
            'submitted_at' => now()->toIso8601String(),
            'data' => $request->input('fields'),
        ];

        if (! is_dir($recordsBase)) {
            mkdir($recordsBase, 0775, true);
        }

        $recordFilename = Str::slug($request->input('title')) . '.rec.json';
        $recordFullPath = $recordsBase . '/' . $recordFilename;

        $counter = 1;
        while (File::exists($recordFullPath)) {
            $recordFilename = Str::slug($request->input('title')) . '-' . $counter . '.rec.json';
            $recordFullPath = $recordsBase . '/' . $recordFilename;
            $counter++;
        }

        File::put($recordFullPath, json_encode($recordData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");

        // Auto-commit to git (records don't go through publish workflow)
        app()->terminating(function () use ($recId) {
            $base = base_path();
            try {
                \Illuminate\Support\Facades\Process::path($base)->run('git pull --no-rebase 2>/dev/null');
                \Illuminate\Support\Facades\Process::path($base)->run('git add qms/records/');
                $diff = \Illuminate\Support\Facades\Process::path($base)->run('git diff --cached --quiet');
                if (! $diff->successful()) {
                    \Illuminate\Support\Facades\Process::path($base)->run(['git', 'commit', '--author', 'QMS System <qms@system>', '-m', "Record {$recId} submitted"]);
                    \Illuminate\Support\Facades\Process::path($base)->run('git push');
                }
            } catch (\Throwable $e) {
                // Silent — file is on disk
            }
        });

        return redirect()->route('records.show', $recordFilename)
            ->with('success', "Form submitted as {$recId}.");
    }


    /**
     * Create a new form template via UI.
     */
    public function create(Request $request)
    {
        if (! in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            abort(403);
        }

        return view('forms.create', [
            'directories' => $this->getDirectories(),
        ]);
    }

    /**
     * Store a new form template.
     */
    public function store(Request $request)
    {
        if (! in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'directory' => 'nullable|string',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,date,select,checkbox,number,email',
        ]);

        $filename = Str::slug($request->input('title')) . '.form.json';
        $directory = $request->input('directory', '');
        if (str_contains($directory, '..')) { abort(403); }
        $relativePath = $directory ? $directory . '/' . $filename : $filename;
        $fullPath = $this->basePath . '/' . $relativePath;

        if (File::exists($fullPath)) {
            return back()->withErrors(['title' => 'A form with this name already exists.'])->withInput();
        }

        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $docId = DocumentMetadata::nextId('FM', $this->basePath);

        $schema = [
            'id' => $docId,
            'title' => $request->input('title'),
            'type' => 'FM',
            'version' => '0.1',
            'status' => 'draft',
            'author' => $request->user()->name,
            'fields' => $request->input('fields'),
        ];

        File::put($fullPath, json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");

        // Log change for git tracking
        \App\Models\DocumentChange::create([
            'user_id' => $request->user()->id,
            'action' => 'create',
            'path' => $relativePath,
        ]);

        return redirect()->route('documents.index', ['path' => $relativePath])
            ->with('success', "Form {$docId} created. Remember to publish when ready.");
    }

    /**
     * Edit a form template.
     */
    public function edit(Request $request, string $path)
    {
        if (! in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            abort(403);
        }

        if (! str_ends_with($path, '.form.json')) {
            $path .= '.form.json';
        }

        $fullPath = $this->basePath . '/' . $path;
        $schema = $this->readForm($fullPath);

        if (! $schema) {
            abort(404, 'Form not found or invalid.');
        }

        // Sidebar data (same as document edit page)
        $docIndex = DocumentMetadata::index($this->basePath);
        $git = app(\App\Services\GitService::class);
        $docController = app(DocumentController::class);
        $tree = $docController->buildTree($this->basePath, '', $docIndex);
        $changedFiles = $git->getChangedFiles();
        $changeLogCount = \App\Models\DocumentChange::count();
        $pendingCount = max(count($changedFiles), $changeLogCount);
        $commentSummary = app(\App\Services\CommentService::class)->summary();

        $sidebarDocs = [];
        foreach ($docIndex as $docPath => $docMeta) {
            $dir = dirname($docPath);
            $docType = $docMeta['id'] ? explode('-', $docMeta['id'])[0] : '';
            $sidebarDocs[] = [
                'path' => $docPath,
                'url_path' => preg_replace('/\.md$/', '', $docPath),
                'doc_id' => $docMeta['id'] ?? null,
                'title' => $docMeta['title'] ?? ucwords(str_replace(['-', '_'], ' ', pathinfo($docPath, PATHINFO_FILENAME))),
                'type' => $docType,
                'status' => $docMeta['status'] ?? 'draft',
                'directory' => ($dir !== '.' && $dir !== '') ? ucwords(str_replace(['-', '_'], ' ', $dir)) : null,
                'is_markdown' => $docMeta['_is_markdown'] ?? true,
            ];
        }

        return view('forms.edit', [
            'schema' => $schema,
            'meta' => $this->formMeta($schema),
            'path' => $path,
            'currentPath' => $path,
            'tree' => $tree,
            'sidebarDocs' => $sidebarDocs,
            'changedFiles' => $changedFiles,
            'pendingCount' => $pendingCount,
            'canEdit' => true,
            'commentSummary' => $commentSummary,
            'directories' => $this->getDirectories(),
        ]);
    }

    /**
     * Update a form template.
     */
    public function update(Request $request)
    {
        if (! in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            abort(403);
        }

        $request->validate([
            'path' => 'required|string',
            'title' => 'required|string|max:255',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,date,select,checkbox,number,email',
        ]);

        $path = $request->input('path');
        if (str_contains($path, '..')) { abort(403); }

        $fullPath = $this->basePath . '/' . $path;
        $existing = $this->readForm($fullPath);

        if (! $existing) {
            abort(404);
        }

        // Preserve id, type, and update the rest
        $schema = [
            'id' => $existing['id'],
            'title' => $request->input('title'),
            'type' => $existing['type'] ?? 'FM',
            'version' => $existing['version'] ?? '0.1',
            'status' => $existing['status'] ?? 'draft',
            'author' => $existing['author'] ?? $request->user()->name,
            'fields' => $request->input('fields'),
        ];

        File::put($fullPath, json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");

        \App\Models\DocumentChange::create([
            'user_id' => $request->user()->id,
            'action' => 'edit',
            'path' => $path,
        ]);

        return redirect()->route('documents.index', ['path' => $path])
            ->with('success', 'Form updated. Remember to publish when ready.');
    }

    private function getDirectories(): array
    {
        $dirs = ['' => '/ (root)'];
        foreach (File::directories($this->basePath) as $dir) {
            $name = basename($dir);
            $dirs[$name] = '/' . $name;
        }
        return $dirs;
    }
}
