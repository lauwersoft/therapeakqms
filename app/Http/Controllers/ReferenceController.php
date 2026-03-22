<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class ReferenceController extends Controller
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/references');
    }

    public function index()
    {
        $files = collect(File::files($this->basePath))
            ->filter(fn ($f) => $f->getExtension() === 'md')
            ->map(function ($file) {
                $filename = $file->getFilenameWithoutExtension();
                $content = File::get($file->getPathname());

                // Extract first H1 as title
                $title = $filename;
                if (preg_match('/^#\s+(.+)$/m', $content, $m)) {
                    $title = $m[1];
                }

                // Categorize
                $category = 'Other';
                if (str_starts_with($filename, 'iso-')) {
                    $category = 'ISO Standards';
                } elseif (str_starts_with($filename, 'eu-mdr')) {
                    $category = 'EU MDR';
                } elseif (str_starts_with($filename, 'mdcg-')) {
                    $category = 'MDCG Guidance';
                }

                return [
                    'filename' => $filename,
                    'title' => $title,
                    'category' => $category,
                    'size' => $file->getSize(),
                ];
            })
            ->sortBy('title')
            ->groupBy('category');

        // Ensure consistent ordering of categories
        $ordered = collect();
        foreach (['ISO Standards', 'EU MDR', 'MDCG Guidance', 'Other'] as $cat) {
            if ($files->has($cat)) {
                $ordered[$cat] = $files[$cat];
            }
        }

        return view('references.index', ['groups' => $ordered]);
    }

    public function show(string $path)
    {
        // Safety check
        if (str_contains($path, '..')) {
            abort(403);
        }

        $filePath = $this->basePath . '/' . $path . '.md';
        if (! File::exists($filePath)) {
            abort(404);
        }

        $raw = File::get($filePath);

        // Extract title from first H1
        $title = $path;
        if (preg_match('/^#\s+(.+)$/m', $raw, $m)) {
            $title = $m[1];
        }

        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($raw)->getContent();

        // Add IDs to h2 and h3 headings for anchor links
        $toc = [];
        $html = preg_replace_callback('/<(h[23])>(.*?)<\/\1>/s', function ($m) use (&$toc) {
            $tag = $m[1];
            $text = strip_tags($m[2]);
            $id = \Illuminate\Support\Str::slug($text);
            if ($tag === 'h2') {
                $toc[] = ['id' => $id, 'title' => $text];
            }
            return '<' . $tag . ' id="' . $id . '">' . $m[2] . '</' . $tag . '>';
        }, $html);

        return view('references.show', [
            'title' => $title,
            'content' => $html,
            'toc' => $toc,
            'path' => $path,
        ]);
    }
}
