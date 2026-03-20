<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\MarkdownConverter;

class DocumentController extends Controller
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/documents');
    }

    public function index(Request $request)
    {
        $tree = $this->buildTree($this->basePath);
        $path = $request->query('path', 'quality-manual.md');

        $filePath = $this->basePath . '/' . $path;

        if (! File::exists($filePath) || ! str_ends_with($filePath, '.md')) {
            abort(404);
        }

        // Prevent directory traversal
        if (! str_starts_with(realpath($filePath), realpath($this->basePath))) {
            abort(403);
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

        return view('documents.index', [
            'tree' => $tree,
            'content' => $html,
            'currentPath' => $path,
        ]);
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

            if (is_dir($entry)) {
                $items[] = [
                    'type' => 'directory',
                    'name' => $this->formatName($name),
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

    private function formatName(string $name): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $name));
    }
}
