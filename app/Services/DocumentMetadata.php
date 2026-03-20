<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class DocumentMetadata
{
    const TYPES = [
        'QM' => 'Quality Manual',
        'POL' => 'Policy',
        'SOP' => 'Standard Operating Procedure',
        'WI' => 'Work Instruction',
        'FM' => 'Form',
        'PLN' => 'Plan',
        'REC' => 'Record',
        'RPT' => 'Report',
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'in_review' => 'In Review',
        'approved' => 'Approved',
        'obsolete' => 'Obsolete',
    ];

    /**
     * Parse frontmatter and content from a markdown file.
     */
    public static function parse(string $content): array
    {
        $meta = [];
        $body = $content;

        if (str_starts_with(trim($content), '---')) {
            $parts = preg_split('/^---\s*$/m', $content, 3);

            if (count($parts) >= 3) {
                try {
                    $meta = Yaml::parse(trim($parts[1])) ?? [];
                } catch (\Exception $e) {
                    $meta = [];
                }
                $body = trim($parts[2]);
            }
        }

        // Defaults
        $meta = array_merge([
            'id' => null,
            'title' => null,
            'type' => null,
            'version' => '0.1',
            'status' => 'draft',
            'effective_date' => null,
            'author' => null,
            'iso_refs' => [],
            'mdr_refs' => [],
        ], $meta);

        return ['meta' => $meta, 'body' => $body];
    }

    /**
     * Build frontmatter + body back into a markdown string.
     */
    public static function build(array $meta, string $body): string
    {
        // Remove null/empty values
        $clean = array_filter($meta, fn ($v) => $v !== null && $v !== '' && $v !== []);

        $yaml = Yaml::dump($clean, 2, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

        return "---\n{$yaml}---\n\n{$body}\n";
    }

    /**
     * Generate the next document ID for a given type prefix.
     */
    public static function nextId(string $typePrefix, string $basePath): string
    {
        $highest = 0;
        $prefix = strtoupper($typePrefix);

        $files = File::allFiles($basePath);
        foreach ($files as $file) {
            if (! str_ends_with($file->getFilename(), '.md')) {
                continue;
            }

            $content = File::get($file->getPathname());
            $parsed = self::parse($content);

            if (! empty($parsed['meta']['id']) && str_starts_with($parsed['meta']['id'], $prefix . '-')) {
                $num = (int) substr($parsed['meta']['id'], strlen($prefix) + 1);
                if ($num > $highest) {
                    $highest = $num;
                }
            }
        }

        return $prefix . '-' . str_pad($highest + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get metadata from all documents for indexing/sorting.
     */
    public static function index(string $basePath): array
    {
        $index = [];

        $files = File::allFiles($basePath);
        foreach ($files as $file) {
            if (! str_ends_with($file->getFilename(), '.md')) {
                continue;
            }

            $relativePath = str_replace($basePath . '/', '', $file->getPathname());
            $content = File::get($file->getPathname());
            $parsed = self::parse($content);

            $index[$relativePath] = $parsed['meta'];
        }

        return $index;
    }
}
