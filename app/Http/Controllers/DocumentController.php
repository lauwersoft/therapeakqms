<?php

namespace App\Http\Controllers;

use App\Models\DocumentChange;
use App\Models\User;
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

    public function index(Request $request)
    {
        $docIndex = DocumentMetadata::index($this->basePath);
        $tree = $this->buildTree($this->basePath, '', $docIndex);
        $path = $request->query('path', 'quality-manual.md');

        $filePath = $this->resolvePath($path);
        if (! $filePath || ! str_ends_with($filePath, '.md')) {
            abort(404);
        }

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

        $canEdit = in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR]);
        $changedFiles = $this->git->getChangedFiles();
        $changeLogCount = DocumentChange::count();
        $pendingCount = max(count($changedFiles), $changeLogCount);

        return view('documents.index', [
            'tree' => $tree,
            'content' => $html,
            'meta' => $meta,
            'currentPath' => $path,
            'canEdit' => $canEdit,
            'directories' => $canEdit ? $this->getDirectories() : [],
            'changedFiles' => $changedFiles,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function edit(Request $request)
    {
        $this->authorizeEditor($request->user());

        $path = $request->query('path');
        $filePath = $this->resolvePath($path);
        if (! $filePath) {
            abort(404);
        }

        $raw = File::get($filePath);
        $parsed = DocumentMetadata::parse($raw);

        return view('documents.edit', [
            'content' => $parsed['body'],
            'meta' => $parsed['meta'],
            'currentPath' => $path,
            'documentTypes' => DocumentMetadata::TYPES,
            'statuses' => DocumentMetadata::STATUSES,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'content' => 'required|string',
            'meta_status' => 'nullable|string|in:' . implode(',', array_keys(DocumentMetadata::STATUSES)),
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

        // Update metadata fields if provided
        if ($request->filled('meta_status')) {
            $meta['status'] = $request->input('meta_status');
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

        return redirect()->route('documents.index', ['path' => $relativePath])
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

        return redirect()->route('documents.index', ['path' => $newPath])
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

        $newFilename = Str::slug($request->input('new_name')) . '.md';
        $directory = dirname($oldPath);
        $newPath = ($directory !== '.') ? $directory . '/' . $newFilename : $newFilename;
        $newFull = $this->basePath . '/' . $newPath;

        if (File::exists($newFull)) {
            return back()->withErrors(['new_name' => 'A file with this name already exists.']);
        }

        rename($oldFull, $newFull);
        $this->logChange($request->user(), 'rename', $newPath, ['old_path' => $oldPath]);

        return redirect()->route('documents.index', ['path' => $newPath])
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

        return redirect()->route('documents.edit', ['path' => $relativePath]);
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
        foreach ($changedFiles as $path => $info) {
            $status = $info['status'];

            if ($status === 'modified') {
                $diffs[$path] = $this->git->getFileDiff($path);
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

        try {
            $this->git->publish($request->user(), $request->input('message'));
        } catch (\RuntimeException $e) {
            return back()->withErrors(['publish' => $e->getMessage()]);
        }

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

    private function buildTree(string $directory, string $prefix = '', array $docIndex = []): array
    {
        $items = [];

        $entries = collect(File::files($directory))
            ->merge(File::directories($directory))
            ->sortBy(fn ($item) => [is_dir($item) ? 0 : 1, basename($item)]);

        foreach ($entries as $entry) {
            $name = basename($entry);
            $relativePath = $prefix ? $prefix . '/' . $name : $name;

            if ($name === '.gitkeep') {
                continue;
            }

            if (is_dir($entry)) {
                $items[] = [
                    'type' => 'directory',
                    'name' => $this->formatName($name),
                    'path' => $relativePath,
                    'children' => $this->buildTree($entry, $relativePath, $docIndex),
                ];
            } elseif (str_ends_with($name, '.md')) {
                $meta = $docIndex[$relativePath] ?? null;
                $items[] = [
                    'type' => 'file',
                    'name' => $meta['title'] ?? $this->formatName(str_replace('.md', '', $name)),
                    'doc_id' => $meta['id'] ?? null,
                    'doc_status' => $meta['status'] ?? null,
                    'path' => $relativePath,
                ];
            }
        }

        // Sort files: by doc_id if present, then by name
        usort($items, function ($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'directory' ? -1 : 1;
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
}
