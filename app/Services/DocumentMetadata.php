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
        'TMP' => 'Template',
        'PLN' => 'Plan',
        'REC' => 'Record',
        'RPT' => 'Report',
        'LOG' => 'Log',
        'LST' => 'List / Register',
        'SPE' => 'Specification',
        'DWG' => 'Drawing / Diagram',
        'AGR' => 'Agreement',
        'CER' => 'Certificate',
        'LBL' => 'Label / IFU',
        'RA' => 'Risk Assessment',
        'CE' => 'Clinical Evaluation',
        'MAN' => 'Manual / Guide',
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'in_review' => 'In Review',
        'approved' => 'Approved',
        'obsolete' => 'Obsolete',
    ];

    const DEFAULTS = [
        'id' => null,
        'title' => null,
        'type' => null,
        'version' => '0.1',
        'status' => 'draft',
        'effective_date' => null,
        'author' => null,
        'iso_refs' => [],
        'mdr_refs' => [],
    ];

    /**
     * Parse frontmatter and content from a markdown file.
     * Robust: handles missing frontmatter, malformed YAML, and --- in body.
     */
    public static function parse(string $content): array
    {
        $meta = [];
        $body = $content;

        // Only parse if content starts with ---
        $trimmed = ltrim($content);
        if (str_starts_with($trimmed, '---')) {
            // Find the closing --- (must be on its own line)
            // Split on the first two --- line boundaries
            $lines = preg_split('/\r?\n/', $trimmed);

            $yamlLines = [];
            $foundOpen = false;
            $closingLine = null;

            for ($i = 0; $i < count($lines); $i++) {
                if ($i === 0 && trim($lines[$i]) === '---') {
                    $foundOpen = true;
                    continue;
                }

                if ($foundOpen && trim($lines[$i]) === '---') {
                    $closingLine = $i;
                    break;
                }

                if ($foundOpen) {
                    $yamlLines[] = $lines[$i];
                }
            }

            if ($foundOpen && $closingLine !== null && ! empty($yamlLines)) {
                $yamlBlock = implode("\n", $yamlLines);
                $body = implode("\n", array_slice($lines, $closingLine + 1));

                try {
                    $parsed = Yaml::parse($yamlBlock);
                    if (is_array($parsed)) {
                        $meta = $parsed;
                    }
                } catch (\Exception $e) {
                    $meta = [];
                    $body = $content;
                }
            }
        }

        // Merge with defaults — ensures all keys exist
        $meta = array_merge(self::DEFAULTS, $meta);

        // Validate known fields
        $meta['status'] = array_key_exists($meta['status'], self::STATUSES) ? $meta['status'] : 'draft';
        $meta['type'] = ($meta['type'] && array_key_exists($meta['type'], self::TYPES)) ? $meta['type'] : $meta['type'];
        $meta['iso_refs'] = is_array($meta['iso_refs']) ? $meta['iso_refs'] : [];
        $meta['mdr_refs'] = is_array($meta['mdr_refs']) ? $meta['mdr_refs'] : [];
        $meta['version'] = (string) ($meta['version'] ?? '0.1');

        // Ensure body is trimmed cleanly
        $body = trim($body);

        return ['meta' => $meta, 'body' => $body];
    }

    /**
     * Build frontmatter + body back into a markdown string.
     * Preserves field order for clean diffs.
     */
    public static function build(array $meta, string $body): string
    {
        // Safety: strip any accidental frontmatter from body
        $body = self::stripFrontmatter($body);

        // Fixed field order for consistent output (clean git diffs)
        $ordered = [];
        $fieldOrder = ['id', 'title', 'type', 'version', 'status', 'effective_date', 'author', 'iso_refs', 'mdr_refs'];

        foreach ($fieldOrder as $key) {
            if (isset($meta[$key]) && $meta[$key] !== null && $meta[$key] !== '' && $meta[$key] !== []) {
                $ordered[$key] = $meta[$key];
            }
        }

        // Include any extra fields not in the standard order
        foreach ($meta as $key => $value) {
            if (! in_array($key, $fieldOrder) && $value !== null && $value !== '' && $value !== []) {
                $ordered[$key] = $value;
            }
        }

        // Build YAML manually for clean, consistent output with double quotes
        $lines = [];
        foreach ($ordered as $key => $value) {
            if (is_array($value)) {
                $lines[] = "{$key}:";
                foreach ($value as $item) {
                    $lines[] = "  - \"{$item}\"";
                }
            } else {
                $escaped = str_replace('"', '\\"', (string) $value);
                $lines[] = "{$key}: \"{$escaped}\"";
            }
        }

        $yaml = implode("\n", $lines) . "\n";
        $body = trim($body);

        return "---\n{$yaml}---\n\n{$body}\n";
    }

    /**
     * Strip any frontmatter from a string (in case body accidentally contains it).
     */
    public static function stripFrontmatter(string $content): string
    {
        $trimmed = ltrim($content);
        if (! str_starts_with($trimmed, '---')) {
            return trim($content);
        }

        $lines = preg_split('/\r?\n/', $trimmed);
        $foundOpen = false;
        $closingLine = null;

        for ($i = 0; $i < count($lines); $i++) {
            if ($i === 0 && trim($lines[$i]) === '---') {
                $foundOpen = true;
                continue;
            }

            if ($foundOpen && trim($lines[$i]) === '---') {
                $closingLine = $i;
                break;
            }
        }

        if ($foundOpen && $closingLine !== null) {
            return trim(implode("\n", array_slice($lines, $closingLine + 1)));
        }

        return trim($content);
    }

    /**
     * Read sidecar metadata for a non-markdown file.
     */
    public static function readSidecar(string $filePath): ?array
    {
        $metaPath = $filePath . '.meta.json';
        if (File::exists($metaPath)) {
            $data = json_decode(File::get($metaPath), true);
            if (is_array($data)) {
                return array_merge(self::DEFAULTS, $data);
            }
        }
        return null;
    }

    /**
     * Write sidecar metadata for a non-markdown file.
     */
    public static function writeSidecar(string $filePath, array $meta): void
    {
        $clean = array_filter($meta, fn ($v) => $v !== null && $v !== '' && $v !== []);
        File::put($filePath . '.meta.json', json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n");
    }

    /**
     * Check if a file is a hidden system file (.gitkeep, .meta.json).
     */
    public static function isSystemFile(string $filename): bool
    {
        return $filename === '.gitkeep'
            || str_ends_with($filename, '.meta.json');
    }

    /**
     * Check if a file is a markdown document.
     */
    public static function isMarkdown(string $filename): bool
    {
        return str_ends_with(strtolower($filename), '.md');
    }

    /**
     * Generate the next document ID for a given type prefix.
     * Scans all files to find the highest existing number.
     */
    public static function nextId(string $typePrefix, string $basePath): string
    {
        $highest = 0;
        $prefix = strtoupper($typePrefix);

        if (! is_dir($basePath)) {
            return $prefix . '-001';
        }

        // Scan both markdown frontmatter and sidecar metadata
        $files = File::allFiles($basePath);
        foreach ($files as $file) {
            $name = $file->getFilename();
            if (self::isSystemFile($name)) continue;

            $id = null;
            if (self::isMarkdown($name)) {
                $content = File::get($file->getPathname());
                $parsed = self::parse($content);
                $id = $parsed['meta']['id'] ?? null;
            } else {
                $sidecar = self::readSidecar($file->getPathname());
                $id = $sidecar['id'] ?? null;
            }

            if ($id && str_starts_with($id, $prefix . '-')) {
                $num = (int) substr($id, strlen($prefix) + 1);
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

        if (! is_dir($basePath)) {
            return $index;
        }

        $files = File::allFiles($basePath);
        foreach ($files as $file) {
            $name = $file->getFilename();
            if (self::isSystemFile($name)) continue;

            $relativePath = str_replace($basePath . '/', '', $file->getPathname());

            if (self::isMarkdown($name)) {
                $content = File::get($file->getPathname());
                $parsed = self::parse($content);
                $index[$relativePath] = $parsed['meta'];
                $index[$relativePath]['_is_markdown'] = true;
            } else {
                $sidecar = self::readSidecar($file->getPathname());
                if ($sidecar) {
                    $index[$relativePath] = $sidecar;
                } else {
                    $index[$relativePath] = array_merge(self::DEFAULTS, [
                        'title' => pathinfo($name, PATHINFO_FILENAME),
                    ]);
                }
                $index[$relativePath]['_is_markdown'] = false;
                $index[$relativePath]['_extension'] = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $index[$relativePath]['_size'] = $file->getSize();
            }
        }

        return $index;
    }

    /**
     * Compare two meta arrays and return human-readable changes.
     */
    public static function diffMeta(array $oldMeta, array $newMeta): array
    {
        $changes = [];
        $labels = [
            'status' => 'Status',
            'version' => 'Version',
            'effective_date' => 'Effective date',
            'author' => 'Author',
            'title' => 'Title',
        ];

        foreach ($labels as $key => $label) {
            $old = $oldMeta[$key] ?? null;
            $new = $newMeta[$key] ?? null;

            if ($key === 'status') {
                $old = self::STATUSES[$old] ?? $old;
                $new = self::STATUSES[$new] ?? $new;
            }

            if ($old !== $new && ($old || $new)) {
                $changes[] = [
                    'field' => $label,
                    'old' => $old,
                    'new' => $new,
                ];
            }
        }

        return $changes;
    }

    /**
     * Get the clean URL path for a document (without .md).
     */
    public static function urlPath(string $filePath): string
    {
        return str_replace('.md', '', $filePath);
    }

    /**
     * Build an ID-to-path lookup map from the document index.
     */
    public static function idMap(array $docIndex): array
    {
        $map = [];
        foreach ($docIndex as $path => $meta) {
            if (! empty($meta['id'])) {
                $map[$meta['id']] = $path;
            }
        }
        return $map;
    }

    /**
     * Resolve [[DOC-ID]] links in HTML content to actual URLs.
     */
    public static function resolveLinks(string $html, array $idMap): string
    {
        return preg_replace_callback('/\[\[([A-Z]+-\d{3})\]\]/', function ($matches) use ($idMap) {
            $docId = $matches[1];
            if (isset($idMap[$docId])) {
                $url = '/qms/' . self::urlPath($idMap[$docId]);
                return '<a href="' . e($url) . '" class="text-blue-600 hover:text-blue-800 font-medium">' . e($docId) . '</a>';
            }
            return '<span class="text-red-500" title="Document not found">' . e($docId) . ' (not found)</span>';
        }, $html);
    }
}
