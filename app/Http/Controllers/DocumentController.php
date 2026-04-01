<?php

namespace App\Http\Controllers;

use App\Models\DocumentChange;
use App\Models\User;
use App\Services\CommentService;
use App\Services\DocumentMetadata;
use App\Services\GitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class DocumentController extends Controller
{
    private string $basePath;
    private GitService $git;

    public function __construct(GitService $git)
    {
        $this->basePath = base_path('qms/documents');
        $this->git = $git;
    }

    public function index(Request $request, ?string $path = null)
    {
        $docIndex = DocumentMetadata::index($this->basePath);
        $tree = $this->buildTree($this->basePath, '', $docIndex);

        // Default to first root-level document
        if (! $path) {
            $rootDoc = collect($docIndex)->filter(fn($meta, $p) => !str_contains($p, '/'))->keys()->first();
            $rootDoc = $rootDoc ?: array_key_first($docIndex);
            $defaultPath = $rootDoc ?: null;
            if ($defaultPath) {
                return redirect()->route('documents.index', ['path' => $defaultPath]);
            }
        }

        // Try to resolve the path — first as-is, then with extensions appended
        $filePath = $this->resolvePath($path);
        if (! $filePath && ! str_contains($path, '.')) {
            // Try common extensions in order of likelihood
            foreach (['.md', '.form.json'] as $ext) {
                $filePath = $this->resolvePath($path . $ext);
                if ($filePath) {
                    $path .= $ext;
                    break;
                }
            }
            // Try any file matching the base name (for PDFs, images, etc.)
            if (! $filePath) {
                $dir = dirname($this->basePath . '/' . $path);
                $base = basename($path);
                if (is_dir($dir)) {
                    foreach (scandir($dir) as $file) {
                        if (str_starts_with($file, $base . '.') && $file !== $base) {
                            $candidate = dirname($path) . '/' . $file;
                            $filePath = $this->resolvePath($candidate);
                            if ($filePath) {
                                $path = $candidate;
                                break;
                            }
                        }
                    }
                }
            }
        }

        if (! $filePath) {
            abort(404);
        }

        $isMarkdown = DocumentMetadata::isMarkdown($path);
        $canEdit = in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR]);
        $changedFiles = $this->git->getChangedFiles();
        $changeLogCount = DocumentChange::count();
        $pendingCount = max(count($changedFiles), $changeLogCount);
        $lastEdit = $this->git->getLastCommitInfo($path);
        $fileHistory = $this->git->getFileHistory($path);

        if ($isMarkdown) {
            $raw = File::get($filePath);
            $parsed = DocumentMetadata::parse($raw);
            $meta = $parsed['meta'];

            $environment = new Environment([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
            $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
            $environment->addExtension(new TableExtension());

            $converter = new MarkdownConverter($environment);
            $html = $converter->convert($parsed['body'])->getContent();

            $idMap = DocumentMetadata::idMap($docIndex);
            $html = DocumentMetadata::resolveLinks($html, $idMap);
            $html = DocumentMetadata::resolveRegulatoryLinks($html);

            // Add IDs to headings for comment section linking
            $html = preg_replace_callback('/<(h[123])>(.*?)<\/\1>/s', function ($m) {
                $tag = $m[1];
                $text = strip_tags($m[2]);
                $id = \Illuminate\Support\Str::slug($text);
                return '<' . $tag . ' id="' . $id . '">' . $m[2] . '</' . $tag . '>';
            }, $html);

            // Convert mermaid code blocks to renderable divs
            $html = preg_replace(
                '/<pre><code class="language-mermaid">(.*?)<\/code><\/pre>/s',
                '<div class="mermaid">$1</div>',
                $html
            );
        } else {
            // For forms/records, read metadata from the JSON itself; for other files, use sidecar
            if (str_ends_with($path, '.form.json') || str_ends_with($path, '.rec.json')) {
                $jsonData = json_decode(File::get($filePath), true);
                $meta = array_merge(DocumentMetadata::DEFAULTS, array_intersect_key($jsonData ?? [], DocumentMetadata::DEFAULTS));
            } else {
                $meta = DocumentMetadata::readSidecar($filePath) ?? array_merge(DocumentMetadata::DEFAULTS, [
                    'title' => pathinfo($path, PATHINFO_FILENAME),
                ]);
            }
            $html = null;
        }

        // File info for non-markdown files
        $isForm = str_ends_with($path, '.form.json');
        $isRecord = str_ends_with($path, '.rec.json');
        $formSchema = null;
        $formSubmissions = null;
        $recordData = null;

        if ($isForm) {
            $formSchema = json_decode(File::get($filePath), true);
            // Load submissions from file-based records
            $formSubmissions = collect();
            $recordsDir = base_path('qms/records');
            if (is_dir($recordsDir)) {
                foreach (File::allFiles($recordsDir) as $recFile) {
                    if (! str_ends_with($recFile->getFilename(), '.rec.json')) continue;
                    try {
                        $recData = json_decode(File::get($recFile->getPathname()), true);
                        $formId = $formSchema['id'] ?? '';
                        if (is_array($recData) && (($recData['form_id'] ?? '') === $formId || ($recData['form_path'] ?? '') === $path)) {
                            $formSubmissions->push([
                                'filename' => $recFile->getFilename(),
                                'id' => $recData['id'] ?? '',
                                'title' => $recData['title'] ?? '',
                                'author' => $recData['author'] ?? '',
                                'submitted_at' => $recData['submitted_at'] ?? null,
                                'status' => $recData['status'] ?? 'submitted',
                            ]);
                        }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
            }
            $formSubmissions = $formSubmissions->sortByDesc('submitted_at');
        }

        if ($isRecord) {
            $recordData = json_decode(File::get($filePath), true);
        }

        $fileInfo = (! $isMarkdown && ! $isForm && ! $isRecord) ? [
            'size' => File::size($filePath),
            'extension' => strtolower(pathinfo($path, PATHINFO_EXTENSION)),
            'mime' => File::mimeType($filePath),
            'filename' => basename($path),
        ] : null;

        // CSV preview data
        $csvData = null;
        if ($fileInfo && in_array($fileInfo['extension'], ['csv', 'tsv'])) {
            $csvData = [];
            $delimiter = $fileInfo['extension'] === 'tsv' ? "\t" : ',';
            if (($handle = fopen($filePath, 'r')) !== false) {
                $row = 0;
                while (($data = fgetcsv($handle, 0, $delimiter)) !== false && $row < 200) {
                    $csvData[] = $data;
                    $row++;
                }
                fclose($handle);
            }
        }

        // Flat doc list for sidebar search
        $sidebarDocs = [];
        foreach ($docIndex as $docPath => $docMeta) {
            $dir = dirname($docPath);
            $docType = $docMeta['id'] ? explode('-', $docMeta['id'])[0] : '';
            $sidebarDocs[] = [
                'path' => $docPath,
                'url_path' => $docPath,
                'doc_id' => $docMeta['id'] ?? null,
                'title' => $docMeta['title'] ?? $this->formatName(pathinfo($docPath, PATHINFO_FILENAME)),
                'type' => $docType,
                'category' => DocumentMetadata::normalizeCategory($docMeta['category'] ?? []),
                'status' => $docMeta['status'] ?? 'draft',
                'directory' => ($dir !== '.' && $dir !== '') ? ucwords(str_replace(['-', '_'], ' ', $dir)) : null,
                'is_markdown' => $docMeta['_is_markdown'] ?? true,
            ];
        }

        // Comments
        $commentService = app(CommentService::class);
        $docComments = $meta['id'] ? $commentService->getVisibleComments($meta['id'], $request->user()->role) : [];
        $commentSummary = $commentService->summary();

        return view('documents.index', [
            'isForm' => $isForm,
            'isRecord' => $isRecord,
            'formSchema' => $formSchema,
            'formSubmissions' => $formSubmissions,
            'recordData' => $recordData,
            'tree' => $tree,
            'fileHistory' => $fileHistory,
            'content' => $html,
            'meta' => $meta,
            'lastEdit' => $lastEdit,
            'currentPath' => $path,
            'isMarkdown' => $isMarkdown,
            'fileInfo' => $fileInfo,
            'csvData' => $csvData ?? null,
            'canEdit' => $canEdit,
            'directories' => $canEdit ? $this->getDirectories() : [],
            'changedFiles' => $changedFiles,
            'pendingCount' => $pendingCount,
            'sidebarDocs' => $sidebarDocs,
            'docComments' => $docComments,
            'commentSummary' => $commentSummary,
        ]);
    }

    public function edit(Request $request, string $path)
    {
        $this->authorizeEditor($request->user());

        if (! str_ends_with($path, '.md')) {
            $path .= '.md';
        }

        $filePath = $this->resolvePath($path);
        if (! $filePath) {
            abort(404);
        }

        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);

        $docIndex = DocumentMetadata::index($this->basePath);
        $docList = collect($docIndex)
            ->filter(fn ($m) => ! empty($m['id']))
            ->map(fn ($m, $p) => ['id' => $m['id'], 'title' => $m['title'] ?? '', 'type' => $m['type'] ?? ''])
            ->values()
            ->sortBy('id')
            ->values()
            ->toArray();

        $tree = $this->buildTree($this->basePath, '', $docIndex);
        $changedFiles = $this->git->getChangedFiles();
        $changeLogCount = DocumentChange::count();
        $pendingCount = max(count($changedFiles), $changeLogCount);
        $lastEdit = $this->git->getLastCommitInfo($path);

        $sidebarDocs = [];
        foreach ($docIndex as $docPath => $docMeta) {
            $dir = dirname($docPath);
            $docType = $docMeta['id'] ? explode('-', $docMeta['id'])[0] : '';
            $sidebarDocs[] = [
                'path' => $docPath,
                'url_path' => $docPath,
                'doc_id' => $docMeta['id'] ?? null,
                'title' => $docMeta['title'] ?? $this->formatName(pathinfo($docPath, PATHINFO_FILENAME)),
                'type' => $docType,
                'category' => DocumentMetadata::normalizeCategory($docMeta['category'] ?? []),
                'status' => $docMeta['status'] ?? 'draft',
                'directory' => ($dir !== '.' && $dir !== '') ? ucwords(str_replace(['-', '_'], ' ', $dir)) : null,
                'is_markdown' => $docMeta['_is_markdown'] ?? true,
            ];
        }

        $commentService = app(CommentService::class);
        $docComments = $parsed['meta']['id'] ? $commentService->getVisibleComments($parsed['meta']['id'], $request->user()->role) : [];
        $commentSummary = $commentService->summary();

        // Form submissions (for meta-header)
        $formSubmissions = null;
        if (str_ends_with($path, '.form.json')) {
            $formSchema = json_decode(File::get($this->basePath . '/' . $path), true);
            $formSubmissions = collect();
            $recordsDir = base_path('qms/records');
            if (is_dir($recordsDir)) {
                $formId = $formSchema['id'] ?? '';
                foreach (File::allFiles($recordsDir) as $recFile) {
                    if (!str_ends_with($recFile->getFilename(), '.rec.json')) continue;
                    try {
                        $recData = json_decode(File::get($recFile->getPathname()), true);
                        if (is_array($recData) && (($recData['form_id'] ?? '') === $formId)) {
                            $formSubmissions->push($recData);
                        }
                    } catch (\Throwable $e) { continue; }
                }
            }
        }

        return view('documents.edit', [
            'content' => $parsed['body'],
            'meta' => $parsed['meta'],
            'currentPath' => $path,
            'documentTypes' => DocumentMetadata::TYPES,
            'statuses' => DocumentMetadata::STATUSES,
            'docList' => $docList,
            'tree' => $tree,
            'sidebarDocs' => $sidebarDocs,
            'changedFiles' => $changedFiles,
            'pendingCount' => $pendingCount,
            'canEdit' => true,
            'lastEdit' => $lastEdit,
            'formSubmissions' => $formSubmissions,
            'directories' => $this->getDirectories(),
            'docComments' => $docComments,
            'commentSummary' => $commentSummary,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'content' => 'required|string',
            'meta_status' => 'nullable|string|in:' . implode(',', array_keys(DocumentMetadata::STATUSES)),
            'meta_category' => 'nullable|array',
            'meta_category.*' => 'string|in:' . implode(',', array_keys(DocumentMetadata::CATEGORIES)),
            'meta_version' => 'nullable|string|max:20',
            'meta_effective_date' => 'nullable|date',
            'meta_author' => 'nullable|string|max:255',
        ]);

        $path = $request->input('path');
        $filePath = $this->resolvePath($path);
        if (! $filePath) {
            abort(404);
        }

        // Read existing frontmatter
        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);
        $meta = $parsed['meta'];

        // If an approved document's body content changed, set to in_review
        if ($meta['status'] === 'approved' && $request->input('content') !== $parsed['body']) {
            $meta['status'] = 'in_review';
        }

        // Update metadata fields if provided (explicit status override takes priority)
        if ($request->filled('meta_status')) {
            $meta['status'] = $request->input('meta_status');
        }
        if ($request->has('meta_category')) {
            $meta['category'] = array_values(array_filter($request->input('meta_category', [])));
        }
        if ($request->filled('meta_version')) {
            $meta['version'] = $request->input('meta_version');
        }
        if ($request->filled('meta_effective_date')) {
            $meta['effective_date'] = $request->input('meta_effective_date');
        }
        if ($request->has('meta_author')) {
            $meta['author'] = $request->input('meta_author');
        }

        // Build the full file with frontmatter + new content
        $body = $request->input('content');
        $fileContent = $meta['id'] ? DocumentMetadata::build($meta, $body) : $body;

        File::put($filePath, $fileContent);
        $this->logChange($request->user(), 'edit', $path);

        return redirect()->route('documents.index', ['path' => $path])
            ->with('success', 'Document saved. Remember to publish when ready.');
    }

    public function updateMeta(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'meta_status' => 'nullable|string|in:' . implode(',', array_keys(DocumentMetadata::STATUSES)),
            'meta_category' => 'nullable|array',
            'meta_category.*' => 'string|in:' . implode(',', array_keys(DocumentMetadata::CATEGORIES)),
            'meta_version' => 'nullable|string|max:20',
            'meta_effective_date' => 'nullable|date',
            'meta_author' => 'nullable|string|max:255',
            'meta_iso_refs' => 'nullable|array',
            'meta_iso_refs.*' => 'string|max:50',
            'meta_mdr_refs' => 'nullable|array',
            'meta_mdr_refs.*' => 'string|max:100',
            'redirect' => 'nullable|string|in:edit,show',
        ]);

        $path = $request->input('path');
        $filePath = $this->resolvePath($path);
        if (! $filePath) {
            abort(404);
        }

        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);
        $meta = $parsed['meta'];

        // Block approval if there are unresolved required changes
        if ($request->input('meta_status') === 'approved' && $meta['id']) {
            $commentService = app(CommentService::class);
            $unresolvedRequired = $commentService->unresolvedRequiredChanges($meta['id']);
            if ($unresolvedRequired > 0) {
                $urlPath = $path;
                $route = $request->input('redirect') === 'edit' ? 'documents.edit' : 'documents.index';
                return redirect()->route($route, ['path' => $urlPath])
                    ->withErrors(["Cannot approve: {$unresolvedRequired} unresolved required " . ($unresolvedRequired === 1 ? 'change' : 'changes') . '. Resolve all required changes before approving.']);
            }
        }

        if ($request->filled('meta_status')) {
            $meta['status'] = $request->input('meta_status');
        }
        if ($request->has('meta_category')) {
            $meta['category'] = array_values(array_filter($request->input('meta_category', [])));
        }
        if ($request->filled('meta_version')) {
            $meta['version'] = $request->input('meta_version');
        }
        if ($request->filled('meta_effective_date')) {
            $meta['effective_date'] = $request->input('meta_effective_date');
        }
        if ($request->has('meta_author')) {
            $meta['author'] = $request->input('meta_author');
        }
        if ($request->has('meta_iso_refs')) {
            $meta['iso_refs'] = array_filter($request->input('meta_iso_refs', []));
        }
        if ($request->has('meta_mdr_refs')) {
            $meta['mdr_refs'] = array_filter($request->input('meta_mdr_refs', []));
        }

        $fileContent = $meta['id'] ? DocumentMetadata::build($meta, $parsed['body']) : $parsed['body'];
        File::put($filePath, $fileContent);
        $this->logChange($request->user(), 'edit', $path, ['fields' => 'properties']);

        $urlPath = $path;
        if ($request->input('redirect') === 'edit') {
            return redirect()->route('documents.edit', ['path' => $urlPath])
                ->with('success', 'Properties saved.');
        }

        return redirect()->route('documents.index', ['path' => $urlPath])
            ->with('success', 'Properties saved. Remember to publish when ready.');
    }

    public function create(Request $request)
    {
        $this->authorizeEditor($request->user());

        $directory = $request->query('directory', '');

        return view('documents.create', [
            'directory' => $directory,
            'directories' => $this->getDirectories(),
            'documentTypes' => DocumentMetadata::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'filename' => 'required|string|max:255',
            'directory' => 'nullable|string',
            'doc_type' => 'required|string|in:' . implode(',', array_keys(DocumentMetadata::TYPES)),
            'content' => 'nullable|string',
        ]);

        $filename = Str::slug($request->input('filename')) . '.md';
        $directory = $request->input('directory', '');
        if (str_contains($directory, '..')) { abort(403); }
        $relativePath = $directory ? $directory . '/' . $filename : $filename;
        $fullPath = $this->basePath . '/' . $relativePath;

        if (File::exists($fullPath)) {
            return back()->withErrors(['filename' => 'A file with this name already exists.'])->withInput();
        }

        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $docType = $request->input('doc_type');
        $docId = DocumentMetadata::nextId($docType, $this->basePath);
        $title = $request->input('filename');

        $meta = [
            'id' => $docId,
            'title' => $title,
            'type' => $docType,
            'version' => '0.1',
            'status' => 'draft',
            'author' => $request->user()->name,
        ];

        $body = $request->input('content', "# {$title}\n");
        $content = DocumentMetadata::build($meta, $body);

        File::put($fullPath, $content);
        $this->logChange($request->user(), 'create', $relativePath);

        return redirect()->route('documents.index', ['path' => preg_replace('/\.md$/', '', $relativePath)])
            ->with('success', 'Document created. Remember to publish when ready.');
    }

    public function move(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'destination' => 'nullable|string',
        ]);

        $oldPath = $request->input('path');
        $destination = $request->input('destination');
        if ($destination && str_contains($destination, '..')) { abort(403); }

        $oldFull = $this->resolvePath($oldPath);
        if (! $oldFull) {
            abort(404);
        }

        $filename = basename($oldPath);
        $newPath = $destination ? $destination . '/' . $filename : $filename;
        $newFull = $this->basePath . '/' . $newPath;

        if (File::exists($newFull)) {
            return back()->withErrors(['destination' => 'A file with this name already exists in the destination.']);
        }

        $newDir = dirname($newFull);
        if (! is_dir($newDir)) {
            mkdir($newDir, 0755, true);
        }

        rename($oldFull, $newFull);
        $this->logChange($request->user(), 'move', $newPath, ['old_path' => $oldPath]);

        return redirect()->route('documents.index', ['path' => preg_replace('/\.md$/', '', $newPath)])
            ->with('success', 'Document moved. Remember to publish when ready.');
    }

    public function rename(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'new_name' => 'required|string|max:255',
        ]);

        $oldPath = $request->input('path');
        $oldFull = $this->resolvePath($oldPath);
        if (! $oldFull) {
            abort(404);
        }

        // Preserve original extension
        $originalExt = pathinfo($oldPath, PATHINFO_EXTENSION);
        $ext = $originalExt ? '.' . $originalExt : '.md';
        $newFilename = Str::slug($request->input('new_name')) . $ext;
        $directory = dirname($oldPath);
        $newPath = ($directory !== '.') ? $directory . '/' . $newFilename : $newFilename;
        $newFull = $this->basePath . '/' . $newPath;

        if (File::exists($newFull)) {
            return back()->withErrors(['new_name' => 'A file with this name already exists.']);
        }

        rename($oldFull, $newFull);
        $this->logChange($request->user(), 'rename', $newPath, ['old_path' => $oldPath]);

        return redirect()->route('documents.index', ['path' => preg_replace('/\.md$/', '', $newPath)])
            ->with('success', 'Document renamed. Remember to publish when ready.');
    }

    public function destroy(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate(['path' => 'required|string']);

        $path = $request->input('path');
        $fullPath = $this->resolvePath($path);
        if (! $fullPath) {
            abort(404);
        }

        $dir = dirname($fullPath);
        unlink($fullPath);
        $this->logChange($request->user(), 'delete', $path);

        // If directory is now empty, add .gitkeep so git still tracks it
        if (is_dir($dir)) {
            $remaining = collect(File::files($dir))->filter(fn ($f) => $f->getFilename() !== '.gitkeep');
            $subDirs = File::directories($dir);
            if ($remaining->isEmpty() && empty($subDirs) && ! File::exists($dir . '/.gitkeep')) {
                File::put($dir . '/.gitkeep', '');
            }
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted. Remember to publish when ready.');
    }

    public function createDirectory(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'name' => 'required|string|max:255',
            'parent' => 'nullable|string',
        ]);

        $name = Str::slug($request->input('name'));
        $parent = $request->input('parent', '');
        $relativePath = $parent ? $parent . '/' . $name : $name;
        $fullPath = $this->basePath . '/' . $relativePath;

        if (is_dir($fullPath)) {
            return back()->withErrors(['name' => 'This directory already exists.']);
        }

        mkdir($fullPath, 0755, true);
        File::put($fullPath . '/.gitkeep', '');
        $this->logChange($request->user(), 'create', $relativePath, ['type' => 'directory']);

        return back()->with('success', 'Directory created.');
    }

    public function renameDirectory(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'new_name' => 'required|string|max:255',
        ]);

        $oldPath = $request->input('path');
        $oldFull = $this->basePath . '/' . $oldPath;

        if (! is_dir($oldFull) || ! str_starts_with(realpath($oldFull), realpath($this->basePath))) {
            abort(404);
        }

        $newName = Str::slug($request->input('new_name'));
        $parent = dirname($oldPath);
        $newPath = ($parent !== '.') ? $parent . '/' . $newName : $newName;
        $newFull = $this->basePath . '/' . $newPath;

        if (is_dir($newFull)) {
            return back()->withErrors(['new_name' => 'A directory with this name already exists.']);
        }

        rename($oldFull, $newFull);
        $this->logChange($request->user(), 'rename', $newPath, ['old_path' => $oldPath, 'type' => 'directory']);

        return redirect()->route('documents.index')
            ->with('success', 'Directory renamed.');
    }

    public function destroyDirectory(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate(['path' => 'required|string']);

        $path = $request->input('path');
        $fullPath = $this->basePath . '/' . $path;

        if (! is_dir($fullPath) || ! str_starts_with(realpath($fullPath), realpath($this->basePath))) {
            abort(404);
        }

        // Check if directory has real files (not just .gitkeep)
        $realFiles = collect(File::allFiles($fullPath))->filter(fn ($f) => $f->getFilename() !== '.gitkeep');
        if ($realFiles->isNotEmpty()) {
            return back()->withErrors(['path' => 'Directory is not empty. Delete all files inside first.']);
        }

        File::deleteDirectory($fullPath);
        $this->logChange($request->user(), 'delete', $path, ['type' => 'directory']);

        return redirect()->route('documents.index')
            ->with('success', 'Directory deleted.');
    }

    public function quickCreate(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'filename' => 'required|string|max:255',
            'directory' => 'nullable|string',
            'doc_type' => 'required|string|in:' . implode(',', array_keys(DocumentMetadata::TYPES)),
        ]);

        $filename = Str::slug($request->input('filename')) . '.md';
        $directory = $request->input('directory', '');
        if (str_contains($directory, '..')) { abort(403); }
        $relativePath = $directory ? $directory . '/' . $filename : $filename;
        $fullPath = $this->basePath . '/' . $relativePath;

        if (File::exists($fullPath)) {
            return back()->withErrors(['filename' => 'A file with this name already exists.']);
        }

        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $docType = $request->input('doc_type');
        $docId = DocumentMetadata::nextId($docType, $this->basePath);
        $title = $request->input('filename');

        $meta = [
            'id' => $docId,
            'title' => $title,
            'type' => $docType,
            'version' => '0.1',
            'status' => 'draft',
            'author' => $request->user()->name,
        ];

        $content = DocumentMetadata::build($meta, "# {$title}\n");
        File::put($fullPath, $content);
        $this->logChange($request->user(), 'create', $relativePath);

        return redirect()->route('documents.edit', ['path' => preg_replace('/\.md$/', '', $relativePath)]);
    }

    public function upload(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'file' => 'required|file|max:51200', // 50MB max
            'directory' => 'nullable|string',
            'doc_type' => 'required|string|in:' . implode(',', array_keys(DocumentMetadata::TYPES)),
            'title' => 'required|string|max:255',
        ]);

        $file = $request->file('file');
        $directory = $request->input('directory', '');
        if (str_contains($directory, '..')) { abort(403); }
        $originalName = $file->getClientOriginalName();
        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . strtolower($file->getClientOriginalExtension());

        $relativePath = $directory ? $directory . '/' . $safeName : $safeName;
        $fullPath = $this->basePath . '/' . $relativePath;

        if (File::exists($fullPath)) {
            return back()->withErrors(['file' => 'A file with this name already exists in the target directory.']);
        }

        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $safeName);

        // Create sidecar metadata
        $docType = $request->input('doc_type');
        $docId = DocumentMetadata::nextId($docType, $this->basePath);

        DocumentMetadata::writeSidecar($fullPath, [
            'id' => $docId,
            'title' => $request->input('title'),
            'type' => $docType,
            'version' => '0.1',
            'status' => 'draft',
            'author' => $request->user()->name,
        ]);

        $this->logChange($request->user(), 'create', $relativePath);

        return redirect()->route('documents.index', ['path' => $relativePath])
            ->with('success', "File uploaded as {$docId}. Remember to publish when ready.");
    }

    public function download(string $path)
    {
        $fullPath = $this->basePath . '/' . $path;

        if (! File::exists($fullPath) || ! str_starts_with(realpath($fullPath), realpath($this->basePath))) {
            abort(404);
        }

        if (request()->query('inline')) {
            return response()->file($fullPath);
        }

        if (auth()->check() && auth()->user()->track_activity) {
            \App\Jobs\TrackUserActionJob::dispatch(auth()->id(), \App\Models\UserActivity::TYPE_DOWNLOAD, $path, null, basename($path), null, request()->ip());
        }

        return response()->download($fullPath);
    }

    public function browse(Request $request)
    {
        $docIndex = DocumentMetadata::index($this->basePath);
        $changedFiles = $this->git->getChangedFiles();

        // Build flat list of all documents with full metadata
        $documents = [];
        foreach ($docIndex as $path => $meta) {
            $dir = dirname($path);
            $changeStatus = isset($changedFiles[$path]) ? $changedFiles[$path]['status'] : null;
            $documents[] = [
                'path' => $path,
                'url_path' => $path,
                'directory' => ($dir !== '.' && $dir !== '') ? ucwords(str_replace(['-', '_', '/'], [' ', ' ', ' / '], $dir)) : 'Root',
                'raw_directory' => ($dir !== '.' && $dir !== '') ? $dir : '',
                'doc_id' => $meta['id'] ?? null,
                'title' => $meta['title'] ?? $this->formatName(preg_replace('/\.md$/', '', basename($path))),
                'type' => $meta['type'] ?? null,
                'type_label' => isset($meta['type']) ? (DocumentMetadata::TYPES[$meta['type']] ?? $meta['type']) : null,
                'type_color' => isset($meta['type']) ? DocumentMetadata::typeColor($meta['type']) : 'bg-gray-100 text-gray-600',
                'category' => $meta['category'] ?? null,
                'category_labels' => collect(DocumentMetadata::normalizeCategory($meta['category'] ?? []))->map(fn($c) => DocumentMetadata::categoryShort($c))->values()->toArray(),
                'category_colors' => collect(DocumentMetadata::normalizeCategory($meta['category'] ?? []))->map(fn($c) => DocumentMetadata::categoryColor($c))->values()->toArray(),
                'status' => $meta['status'] ?? 'draft',
                'status_label' => DocumentMetadata::STATUSES[$meta['status'] ?? 'draft'] ?? 'Draft',
                'version' => $meta['version'] ?? null,
                'author' => $meta['author'] ?? null,
                'changed' => $changeStatus,
            ];
        }

        // Sort by directory then doc_id
        usort($documents, function ($a, $b) {
            $dirCmp = strcasecmp($a['raw_directory'], $b['raw_directory']);
            if ($dirCmp !== 0) return $dirCmp;
            return strnatcmp($a['doc_id'] ?? '', $b['doc_id'] ?? '');
        });

        // Get unique directories
        $directories = collect($documents)->pluck('directory')->unique()->values()->toArray();

        $grouped = collect($documents)->groupBy('raw_directory');
        $canEdit = in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR]);

        $pendingCount = max(count($changedFiles), DocumentChange::count());
        $commentSummary = app(CommentService::class)->summary();

        // Add comment counts to documents
        foreach ($documents as &$doc) {
            $doc['comment_count'] = $commentSummary[$doc['doc_id'] ?? '']['unresolved'] ?? 0;
        }

        return view('documents.browser', [
            'documents' => $documents,
            'grouped' => $grouped,
            'totalDocs' => count($documents),
            'canEdit' => $canEdit,
            'directories' => $canEdit ? $this->getDirectories() : [],
            'changedFiles' => $changedFiles,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function revision(Request $request, string $hash)
    {
        $commit = $this->git->getCommitDetail($hash);
        if (! $commit) {
            abort(404);
        }

        $docIndex = DocumentMetadata::index($this->basePath);
        foreach ($commit['files'] as &$file) {
            $meta = $docIndex[$file['path']] ?? null;
            $file['doc_id'] = $meta['id'] ?? null;
            $file['doc_title'] = $meta['title'] ?? $this->formatName(preg_replace('/\.md$/', '', basename($file['path'])));
        }

        return view('documents.revision', [
            'commit' => $commit,
        ]);
    }

    public function history(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $commits = $this->git->getHistory($perPage, $offset);
        $totalCommits = $this->git->getHistoryCount();
        $totalPages = max(1, ceil($totalCommits / $perPage));

        // Enrich file paths with document metadata
        $docIndex = DocumentMetadata::index($this->basePath);
        foreach ($commits as &$commit) {
            foreach ($commit['files'] as &$file) {
                $meta = $docIndex[$file['path']] ?? null;
                $file['doc_id'] = $meta['id'] ?? null;
                $file['doc_title'] = $meta['title'] ?? $this->formatName(preg_replace('/\.md$/', '', basename($file['path'])));
                $file['doc_type'] = $meta['type'] ?? null;
            }
        }

        return view('documents.history', [
            'commits' => $commits,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCommits' => $totalCommits,
        ]);
    }

    public function changes(Request $request)
    {
        $this->authorizeEditor($request->user());

        $changedFiles = $this->git->getChangedFiles();

        // Reconcile: remove log entries for files that aren't actually changed
        // Keep directory entries (they don't show in git status since .gitkeep is filtered)
        $changedPaths = array_keys($changedFiles);
        DocumentChange::whereNotIn('path', $changedPaths)
            ->where(function ($q) {
                $q->whereNull('details')
                  ->orWhereJsonDoesntContain('details->type', 'directory');
            })
            ->delete();

        // Add directory changes from log that aren't in git
        $dirChanges = DocumentChange::whereJsonContains('details->type', 'directory')->get();
        foreach ($dirChanges as $dc) {
            if (! isset($changedFiles[$dc->path])) {
                $changedFiles[$dc->path] = [
                    'status' => $dc->action,
                    'type' => 'directory',
                ];
                if ($dc->action === 'rename' && isset($dc->details['old_path'])) {
                    $changedFiles[$dc->path]['old_path'] = $dc->details['old_path'];
                }
            }
        }

        $changeLog = DocumentChange::with('user')->oldest()->get();

        // Get diffs for each changed file
        $diffs = [];
        $metaChanges = [];
        foreach ($changedFiles as $path => $info) {
            $status = $info['status'];

            if ($status === 'modified') {
                // Get raw diff for body content
                $diffs[$path] = $this->git->getFileDiff($path);

                // Compare metadata between published and current version
                $originalRaw = $this->git->getOriginalContent($path);
                $currentRaw = File::exists($this->basePath . '/' . $path) ? File::get($this->basePath . '/' . $path) : '';

                if ($originalRaw) {
                    $oldMeta = DocumentMetadata::parse($originalRaw)['meta'];
                    $newMeta = DocumentMetadata::parse($currentRaw)['meta'];
                    $propChanges = DocumentMetadata::diffMeta($oldMeta, $newMeta);
                    if (! empty($propChanges)) {
                        $metaChanges[$path] = $propChanges;
                    }
                }
            } elseif (in_array($status, ['new', 'added'])) {
                $fullPath = $this->basePath . '/' . $path;
                if (File::exists($fullPath)) {
                    $diffs[$path] = File::get($fullPath);
                }
            } elseif ($status === 'deleted') {
                // Show what was deleted
                $original = $this->git->getOriginalContent($path);
                if ($original) {
                    $diffs[$path] = $original;
                }
            } elseif (in_array($status, ['move', 'rename'])) {
                // For moves/renames, show diff between old and new content
                $oldPath = $info['old_path'] ?? null;
                if ($oldPath) {
                    $oldContent = $this->git->getOriginalContent($oldPath);
                    $fullPath = $this->basePath . '/' . $path;
                    $newContent = File::exists($fullPath) ? File::get($fullPath) : '';

                    if ($oldContent === $newContent) {
                        // Just moved, no content changes
                        $diffs[$path] = null;
                    } else {
                        // Moved and content changed
                        $diffs[$path] = $this->git->getFileDiff($path);
                    }
                }
            }
        }

        return view('documents.changes', [
            'changedFiles' => $changedFiles,
            'changeLog' => $changeLog,
            'diffs' => $diffs,
            'metaChanges' => $metaChanges,
            'canPublish' => $request->user()->role === User::ROLE_ADMIN,
        ]);
    }

    public function publish(Request $request)
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Capture changed files before publishing (for notifications)
        $changedFiles = $this->git->getChangedFiles();
        $docIndex = DocumentMetadata::index($this->basePath);
        $notifyFiles = [];
        foreach ($changedFiles as $path => $info) {
            $meta = $docIndex[$path] ?? null;
            $notifyFiles[] = [
                'path' => $path,
                'status' => $info['status'] ?? 'modified',
                'doc_id' => $meta['id'] ?? null,
                'doc_title' => $meta['title'] ?? basename($path),
            ];
        }

        try {
            $this->git->publish($request->user(), $request->input('message'));
        } catch (\RuntimeException $e) {
            return back()->withErrors(['publish' => $e->getMessage()]);
        }

        if ($request->user()->track_activity) {
            \App\Jobs\TrackUserActionJob::dispatch($request->user()->id, \App\Models\UserActivity::TYPE_PUBLISH, $request->path(), null, null, $request->input('message'), $request->ip());
        }

        // Send publication notifications after response
        $publisherName = $request->user()->name;
        app()->terminating(function () use ($publisherName, $notifyFiles) {
            if (!empty($notifyFiles)) {
                \App\Services\QmsNotificationService::documentsPublished($publisherName, $notifyFiles);
            }
        });

        return redirect()->route('documents.index')
            ->with('success', 'All changes published successfully.');
    }

    public function discard(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate(['path' => 'required|string']);

        $this->git->discard($request->input('path'));

        return back()->with('success', 'Changes discarded.');
    }

    public function discardAll(Request $request)
    {
        if ($request->user()->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        $this->git->discardAll();

        return redirect()->route('documents.index')
            ->with('success', 'All changes discarded.');
    }

    private function authorizeEditor($user): void
    {
        if (! in_array($user->role, [User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            abort(403);
        }
    }

    private function logChange(User $user, string $action, string $path, ?array $details = null): void
    {
        DocumentChange::create([
            'user_id' => $user->id,
            'action' => $action,
            'path' => $path,
            'details' => $details,
        ]);
    }

    private function resolvePath(string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $fullPath = $this->basePath . '/' . $path;

        if (! File::exists($fullPath)) {
            return null;
        }

        if (! str_starts_with(realpath($fullPath), realpath($this->basePath))) {
            return null;
        }

        return $fullPath;
    }

    public function buildTree(string $directory, string $prefix = '', array $docIndex = []): array
    {
        $items = [];

        $entries = collect(File::files($directory))
            ->merge(File::directories($directory))
            ->sortBy(fn ($item) => [is_dir($item) ? 0 : 1, basename($item)]);

        foreach ($entries as $entry) {
            $name = basename($entry);
            $relativePath = $prefix ? $prefix . '/' . $name : $name;

            if (DocumentMetadata::isSystemFile($name)) {
                continue;
            }

            if (is_dir($entry)) {
                $items[] = [
                    'type' => 'directory',
                    'name' => $this->formatName($name),
                    'path' => $relativePath,
                    'children' => $this->buildTree($entry, $relativePath, $docIndex),
                ];
            } else {
                $meta = $docIndex[$relativePath] ?? null;
                $isMarkdown = DocumentMetadata::isMarkdown($name);
                $items[] = [
                    'type' => 'file',
                    'name' => $meta['title'] ?? $this->formatName(pathinfo($name, PATHINFO_FILENAME)),
                    'doc_id' => $meta['id'] ?? null,
                    'doc_category' => $meta['category'] ?? null,
                    'doc_status' => $meta['status'] ?? null,
                    'is_markdown' => $isMarkdown,
                    'extension' => strtolower(pathinfo($name, PATHINFO_EXTENSION)),
                    'path' => $relativePath,
                ];
            }
        }

        // Sort: files first (by doc_id), then directories
        usort($items, function ($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'file' ? -1 : 1;
            }
            $aId = $a['doc_id'] ?? '';
            $bId = $b['doc_id'] ?? '';
            if ($aId && $bId) {
                return strnatcmp($aId, $bId);
            }
            return strcasecmp($a['name'], $b['name']);
        });

        return $items;
    }

    private function getDirectories(string $directory = '', string $prefix = ''): array
    {
        $path = $this->basePath . ($directory ? '/' . $directory : '');
        $dirs = ['' => '/ (root)'];

        foreach (File::directories($path) as $dir) {
            $name = basename($dir);
            $relative = $prefix ? $prefix . '/' . $name : $name;
            $dirs[$relative] = '/' . $relative;
            $dirs = array_merge($dirs, $this->getDirectories($relative, $relative));
        }

        return $dirs;
    }

    private function formatName(string $name): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }

    /**
     * Sanitize a user-provided path to prevent directory traversal.
     * Returns null if the path is invalid.
     */
    private function sanitizePath(string $path): ?string
    {
        // Block any path containing ..
        if (str_contains($path, '..')) {
            return null;
        }

        // Normalize slashes and remove leading/trailing slashes
        $path = trim(str_replace('\\', '/', $path), '/');

        if (empty($path)) {
            return null;
        }

        return $path;
    }

    /**
     * Safely strip .md extension from end of path only.
     */
    private function stripMdExtension(string $path): string
    {
        return $path;
    }
}
