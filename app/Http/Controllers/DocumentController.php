<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $tree = $this->buildTree($this->basePath);
        $path = $request->query('path', 'quality-manual.md');

        $filePath = $this->resolvePath($path);
        if (! $filePath || ! str_ends_with($filePath, '.md')) {
            abort(404);
        }

        $markdown = File::get($filePath);

        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($markdown)->getContent();

        $canEdit = in_array($request->user()->role, [User::ROLE_ADMIN, User::ROLE_EDITOR]);

        return view('documents.index', [
            'tree' => $tree,
            'content' => $html,
            'currentPath' => $path,
            'canEdit' => $canEdit,
            'directories' => $canEdit ? $this->getDirectories() : [],
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

        $content = File::get($filePath);
        $tree = $this->buildTree($this->basePath);

        return view('documents.edit', [
            'tree' => $tree,
            'content' => $content,
            'currentPath' => $path,
            'canEdit' => true,
        ]);
    }

    public function update(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'content' => 'required|string',
        ]);

        $path = $request->input('path');
        $filePath = $this->resolvePath($path);
        if (! $filePath) {
            abort(404);
        }

        File::put($filePath, $request->input('content'));
        $this->git->commitAndPush($request->user(), "Edit: {$path}");

        return redirect()->route('documents.index', ['path' => $path])
            ->with('success', 'Document saved.');
    }

    public function create(Request $request)
    {
        $this->authorizeEditor($request->user());

        $directory = $request->query('directory', '');
        $tree = $this->buildTree($this->basePath);

        return view('documents.create', [
            'tree' => $tree,
            'directory' => $directory,
            'directories' => $this->getDirectories(),
            'canEdit' => true,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'filename' => 'required|string|max:255',
            'directory' => 'nullable|string',
            'content' => 'nullable|string',
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

        $content = $request->input('content', "# " . $request->input('filename') . "\n");
        File::put($fullPath, $content);

        $this->git->commitAndPush($request->user(), "Create: {$relativePath}");

        return redirect()->route('documents.index', ['path' => $relativePath])
            ->with('success', 'Document created.');
    }

    public function move(Request $request)
    {
        $this->authorizeEditor($request->user());

        $request->validate([
            'path' => 'required|string',
            'destination' => 'required|string',
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

        $this->git->moveFile($oldPath, $newPath, $request->user());

        return redirect()->route('documents.index', ['path' => $newPath])
            ->with('success', 'Document moved.');
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

        $this->git->moveFile($oldPath, $newPath, $request->user());

        return redirect()->route('documents.index', ['path' => $newPath])
            ->with('success', 'Document renamed.');
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

        $this->git->deleteFile($path, $request->user());

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted.');
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

        // Git doesn't track empty directories, create a .gitkeep
        File::put($fullPath . '/.gitkeep', '');
        $this->git->commitAndPush($request->user(), "Create directory: {$relativePath}");

        return back()->with('success', 'Directory created.');
    }

    private function authorizeEditor($user): void
    {
        if (! in_array($user->role, [User::ROLE_ADMIN, User::ROLE_EDITOR])) {
            abort(403);
        }
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

    private function buildTree(string $directory, string $prefix = ''): array
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
                    'children' => $this->buildTree($entry, $relativePath),
                ];
            } elseif (str_ends_with($name, '.md')) {
                $items[] = [
                    'type' => 'file',
                    'name' => $this->formatName(str_replace('.md', '', $name)),
                    'path' => $relativePath,
                ];
            }
        }

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
