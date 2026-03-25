<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
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

        $submission = FormSubmission::create([
            'form_id' => $schema['id'] ?? basename($path),
            'form_path' => $path,
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'data' => $request->input('fields'),
            'status' => 'submitted',
        ]);

        return redirect()->route('forms.submission', $submission)
            ->with('success', 'Form submitted successfully.');
    }

    /**
     * View a submission.
     */
    public function submission(FormSubmission $submission)
    {
        $fullPath = $this->basePath . '/' . $submission->form_path;
        $schema = $this->readForm($fullPath);
        $meta = $schema ? $this->formMeta($schema) : null;

        return view('forms.submission', [
            'submission' => $submission,
            'schema' => $schema,
            'meta' => $meta,
        ]);
    }

    /**
     * List all submissions for a form.
     */
    public function submissions(Request $request, string $formId)
    {
        $submissions = FormSubmission::where('form_id', $formId)
            ->with('user')
            ->latest()
            ->get();

        return view('forms.submissions', [
            'submissions' => $submissions,
            'formId' => $formId,
        ]);
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

        return view('forms.edit', [
            'schema' => $schema,
            'meta' => $this->formMeta($schema),
            'path' => $path,
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
