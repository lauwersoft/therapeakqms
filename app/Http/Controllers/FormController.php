<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\User;
use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FormController extends Controller
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/documents');
    }

    /**
     * Show a form template (view mode or fill mode).
     */
    public function show(Request $request, string $path)
    {
        if (! str_ends_with($path, '.form.json')) {
            $path .= '.form.json';
        }

        $fullPath = $this->basePath . '/' . $path;
        if (! File::exists($fullPath)) {
            abort(404);
        }

        $schema = json_decode(File::get($fullPath), true);
        $meta = DocumentMetadata::readSidecar($fullPath) ?? DocumentMetadata::DEFAULTS;

        return view('forms.show', [
            'schema' => $schema,
            'meta' => $meta,
            'path' => $path,
        ]);
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
        if (! File::exists($fullPath)) {
            abort(404);
        }

        $schema = json_decode(File::get($fullPath), true);
        $meta = DocumentMetadata::readSidecar($fullPath) ?? DocumentMetadata::DEFAULTS;

        return view('forms.fill', [
            'schema' => $schema,
            'meta' => $meta,
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
        $meta = DocumentMetadata::readSidecar($fullPath);

        $submission = FormSubmission::create([
            'form_id' => $meta['id'] ?? basename($path),
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
        $schema = File::exists($fullPath) ? json_decode(File::get($fullPath), true) : null;
        $meta = $fullPath && File::exists($fullPath) ? DocumentMetadata::readSidecar($fullPath) : null;

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

        $filename = \Illuminate\Support\Str::slug($request->input('title')) . '.form.json';
        $directory = $request->input('directory', '');
        $relativePath = $directory ? $directory . '/' . $filename : $filename;
        $fullPath = $this->basePath . '/' . $relativePath;

        if (File::exists($fullPath)) {
            return back()->withErrors(['title' => 'A form with this name already exists.'])->withInput();
        }

        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $schema = [
            'title' => $request->input('title'),
            'fields' => $request->input('fields'),
        ];

        File::put($fullPath, json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");

        $docId = DocumentMetadata::nextId('FM', $this->basePath);
        DocumentMetadata::writeSidecar($fullPath, [
            'id' => $docId,
            'title' => $request->input('title'),
            'type' => 'FM',
            'version' => '0.1',
            'status' => 'draft',
            'author' => $request->user()->name,
        ]);

        return redirect()->route('documents.index', ['path' => $relativePath])
            ->with('success', "Form {$docId} created.");
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
