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

                // Extract first H1 as title (strip footnote markers)
                $title = $filename;
                if (preg_match('/^#\s+(.+)$/m', $content, $m)) {
                    $title = preg_replace('/\[\^\d+\]/', '', $m[1]);
                    $title = trim($title);
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

                // Extract date
                $date = null;
                if (preg_match('/^((?:January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{4}(?:\s+rev\.?\s*\d+)?)/mi', $content, $dm)) {
                    $date = $dm[1];
                }

                return [
                    'filename' => $filename,
                    'title' => $title,
                    'category' => $category,
                    'date' => $date,
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

        // Extract title from first H1 (strip footnote markers)
        $title = $path;
        if (preg_match('/^#\s+(.+)$/m', $raw, $m)) {
            $title = preg_replace('/\[\^\d+\]/', '', $m[1]);
            $title = trim($title);
        }

        // Extract date — look for lines like "October 2021", "March 2020", "June 2025 rev.1"
        $date = null;
        if (preg_match('/^((?:January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{4}(?:\s+rev\.?\s*\d+)?)/mi', $raw, $dm)) {
            $date = $dm[1];
        }

        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'footnote' => [
                'backref_class' => 'footnote-backref',
                'container_add_hr' => false,
                'container_class' => 'footnotes',
            ],
        ]);
        $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
        $environment->addExtension(new TableExtension());
        $environment->addExtension(new \League\CommonMark\Extension\Footnote\FootnoteExtension());
        $environment->addExtension(new \League\CommonMark\Extension\Autolink\AutolinkExtension());

        $converter = new MarkdownConverter($environment);
        $html = $converter->convert($raw)->getContent();

        // Style date lines (e.g. "October 2021", "June 2025 rev.1") as badges
        $html = preg_replace(
            '/<p>((?:January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{4}(?:\s*(?:\/\s*(?:January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{4})?)?(?:\s+rev\.?\s*\d+)?)<\/p>/i',
            '<p><span class="inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full"><svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>$1</span></p>',
            $html
        );

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

        // File list for sidebar
        $files = $this->getFileList();

        return view('references.show', [
            'title' => $title,
            'date' => $date,
            'content' => $html,
            'toc' => $toc,
            'path' => $path,
            'files' => $files,
        ]);
    }

    private function getFileList(): array
    {
        return collect(File::files($this->basePath))
            ->filter(fn ($f) => $f->getExtension() === 'md')
            ->map(function ($file) {
                $filename = $file->getFilenameWithoutExtension();
                $content = File::get($file->getPathname());
                $title = $filename;
                if (preg_match('/^#\s+(.+)$/m', $content, $m)) {
                    $title = $m[1];
                }
                $category = 'Other';
                if (str_starts_with($filename, 'iso-')) {
                    $category = 'ISO Standards';
                } elseif (str_starts_with($filename, 'eu-mdr')) {
                    $category = 'EU MDR';
                } elseif (str_starts_with($filename, 'mdcg-')) {
                    $category = 'MDCG Guidance';
                }
                return ['filename' => $filename, 'title' => $title, 'category' => $category];
            })
            ->sortBy('title')
            ->values()
            ->toArray();
    }
}
