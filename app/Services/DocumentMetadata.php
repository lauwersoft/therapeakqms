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

    const TYPE_COLORS = [
        'QM' => 'bg-indigo-100 text-indigo-700',      // deep blue-purple
        'POL' => 'bg-violet-100 text-violet-700',      // violet
        'SOP' => 'bg-blue-100 text-blue-700',          // blue
        'WI' => 'bg-cyan-100 text-cyan-700',           // cyan
        'FM' => 'bg-purple-100 text-purple-700',       // purple
        'TMP' => 'bg-fuchsia-100 text-fuchsia-700',   // fuchsia
        'PLN' => 'bg-teal-100 text-teal-700',          // teal
        'REC' => 'bg-orange-100 text-orange-700',      // orange
        'RPT' => 'bg-sky-100 text-sky-700',            // sky blue
        'LOG' => 'bg-gray-100 text-gray-600',          // gray
        'LST' => 'bg-zinc-100 text-zinc-700',          // dark gray
        'SPE' => 'bg-yellow-100 text-yellow-700',      // yellow
        'DWG' => 'bg-emerald-100 text-emerald-700',    // emerald green
        'AGR' => 'bg-amber-100 text-amber-700',        // amber
        'CER' => 'bg-lime-100 text-lime-700',          // lime green
        'LBL' => 'bg-stone-100 text-stone-600',        // stone/brown
        'RA' => 'bg-red-100 text-red-700',             // red
        'CE' => 'bg-rose-100 text-rose-700',           // rose/pink
        'MAN' => 'bg-pink-100 text-pink-700',          // pink
    ];

    const STATUSES = [
        'draft' => 'Draft',
        'in_review' => 'In Review',
        'approved' => 'Approved',
        'obsolete' => 'Obsolete',
    ];

    const CATEGORIES = [
        'qms' => 'Quality System',
        'technical' => 'Technical Documentation',
    ];

    const CATEGORY_COLORS = [
        'qms' => 'bg-blue-50 text-blue-600',
        'technical' => 'bg-amber-50 text-amber-600',
    ];

    const DEFAULTS = [
        'id' => null,
        'title' => null,
        'type' => null,
        'category' => null,
        'version' => '0.1',
        'status' => 'draft',
        'effective_date' => null,
        'author' => null,
        'iso_refs' => [],
        'mdr_refs' => [],
    ];

    public static function categoryColor(string $category): string
    {
        return self::CATEGORY_COLORS[$category] ?? 'bg-gray-50 text-gray-500';
    }

    public static function categoryLabel(string $category): string
    {
        return self::CATEGORIES[$category] ?? ucfirst($category);
    }

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
        $fieldOrder = ['id', 'title', 'type', 'category', 'version', 'status', 'effective_date', 'author', 'iso_refs', 'mdr_refs'];

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
    /**
     * Get CSS classes for a document type badge.
     */
    public static function typeColor(string $type): string
    {
        return self::TYPE_COLORS[$type] ?? 'bg-gray-100 text-gray-600';
    }

    public static function isSystemFile(string $filename): bool
    {
        return $filename === '.gitkeep'
            || str_ends_with($filename, '.meta.json');
    }

    /**
     * Check if a file is a form template.
     */
    public static function isForm(string $filename): bool
    {
        return str_ends_with(strtolower($filename), '.form.json');
    }

    /**
     * Check if a file is a form record/submission.
     */
    public static function isRecord(string $filename): bool
    {
        return str_ends_with(strtolower($filename), '.rec.json');
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
            } elseif (self::isForm($name) || self::isRecord($name)) {
                $data = @json_decode(File::get($file->getPathname()), true);
                $id = is_array($data) ? ($data['id'] ?? null) : null;
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

        $next = $highest + 1;
        $pad = max(3, strlen((string) $next));
        return $prefix . '-' . str_pad($next, $pad, '0', STR_PAD_LEFT);
    }

    /**
     * Check if a document ID already exists.
     */
    public static function idExists(string $id, string $basePath): bool
    {
        $index = self::index($basePath);
        foreach ($index as $meta) {
            if (($meta['id'] ?? null) === $id) {
                return true;
            }
        }
        return false;
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
            } elseif (self::isForm($name) || self::isRecord($name)) {
                $data = @json_decode(File::get($file->getPathname()), true);
                $data = is_array($data) ? $data : [];
                $index[$relativePath] = array_merge(self::DEFAULTS, array_intersect_key($data, self::DEFAULTS));
                $index[$relativePath]['_is_markdown'] = false;
                $index[$relativePath]['_is_form'] = self::isForm($name);
                $index[$relativePath]['_is_record'] = self::isRecord($name);
                $index[$relativePath]['_extension'] = 'form';
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
        return $filePath;
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
        return preg_replace_callback('/\[\[([A-Z]+-\d{3,})\]\]/', function ($matches) use ($idMap) {
            $docId = $matches[1];
            if (isset($idMap[$docId])) {
                $url = '/documents/' . self::urlPath($idMap[$docId]);
                return '<a href="' . e($url) . '" class="text-blue-600 hover:text-blue-800 font-medium">' . e($docId) . '</a>';
            }
            return '<span class="text-red-500" title="Document not found">' . e($docId) . ' (not found)</span>';
        }, $html);
    }

    /**
     * Auto-link regulatory references (ISO, MDR, MDCG, IEC, Articles, Annexes, Clauses) in rendered HTML.
     * Skips text already inside <a> tags.
     */
    public static function resolveRegulatoryLinks(string $html): string
    {
        $linkClass = 'text-blue-600 hover:text-blue-800';

        // Process text that is NOT inside <a> tags or HTML tags
        return preg_replace_callback('/(<a\b[^>]*>.*?<\/a>)|(<[^>]+>)|([^<]+)/s', function ($m) use ($linkClass) {
            // Already a link or HTML tag — skip
            if (!empty($m[1]) || !empty($m[2])) {
                return $m[0];
            }

            $text = $m[3];

            // Use placeholders to prevent double-linking.
            // Each replacement inserts a placeholder, final step restores them.
            $placeholders = [];
            $i = 0;

            $placeholder = function ($link) use (&$placeholders, &$i) {
                $key = "\x00REGLINK" . ($i++) . "\x00";
                $placeholders[$key] = $link;
                return $key;
            };

            // 1. MDCG documents (dynamically from reference files)
            static $mdcgFiles = null;
            if ($mdcgFiles === null) {
                $mdcgFiles = [];
                foreach (glob(base_path('qms/references/mdcg-*.md')) as $f) {
                    $mdcgFiles[] = str_replace(['mdcg-', '.md'], '', basename($f));
                }
                usort($mdcgFiles, fn($a, $b) => strlen($b) - strlen($a));
            }
            foreach ($mdcgFiles as $mdcg) {
                $escaped = preg_quote($mdcg, '/');
                $text = preg_replace_callback('/\bMDCG\s+' . $escaped . '\b/', function ($r) use ($mdcg, $linkClass, $placeholder) {
                    return $placeholder('<a href="/references/mdcg-' . $mdcg . '" class="' . $linkClass . '">' . $r[0] . '</a>');
                }, $text);
            }

            // 2. ISO 13485 with clause: "ISO 13485:2016 Clause 4.2.4"
            $text = preg_replace_callback('/\bISO 13485(?::2016)?\s+Clause\s+([\d.]+)/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'clause-' . str_replace('.', '-', $r[1]);
                return $placeholder('<a href="/references/iso-13485#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 3. ISO 13485 bare: "ISO 13485:2016" or "ISO 13485"
            $text = preg_replace_callback('/\bISO 13485(?::2016)?/', function ($r) use ($linkClass, $placeholder) {
                return $placeholder('<a href="/references/iso-13485" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 4. ISO 14971 with clause
            $text = preg_replace_callback('/\bISO 14971(?::2019)?\s+Clause\s+([\d.]+)/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'clause-' . str_replace('.', '-', $r[1]);
                return $placeholder('<a href="/references/iso-14971#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 5. ISO 14971 bare
            $text = preg_replace_callback('/\bISO 14971(?::2019)?/', function ($r) use ($linkClass, $placeholder) {
                return $placeholder('<a href="/references/iso-14971" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 6. IEC standards (no reference page — bold only)
            $text = preg_replace_callback('/\bIEC 62304(?:[:\-]\d+)?(?:\+A1:\d+)?/', function ($r) use ($placeholder) {
                return $placeholder('<strong>' . $r[0] . '</strong>');
            }, $text);
            $text = preg_replace_callback('/\bIEC 62366(?:-1)?(?::\d+)?/', function ($r) use ($placeholder) {
                return $placeholder('<strong>' . $r[0] . '</strong>');
            }, $text);

            // 7. "EU MDR 2017/745 Article N(N)"
            $text = preg_replace_callback('/\bEU MDR(?:\s+2017\/745)?\s+Article\s+(\d+)(?:\(\d+\))?/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'article-' . $r[1];
                return $placeholder('<a href="/references/eu-mdr#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 8. "EU MDR 2017/745 Annex XIV Part B" etc
            $text = preg_replace_callback('/\bEU MDR(?:\s+2017\/745)?\s+Annex\s+([IVXLC]+)\b/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'annex-' . strtolower($r[1]);
                return $placeholder('<a href="/references/eu-mdr#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 9. Bare "EU MDR 2017/745" or "EU MDR"
            $text = preg_replace_callback('/\bEU MDR(?:\s+2017\/745)?/', function ($r) use ($linkClass, $placeholder) {
                return $placeholder('<a href="/references/eu-mdr" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 10. Standalone "Article N(N)" (not already captured by EU MDR pattern)
            $text = preg_replace_callback('/\bArticle\s+(\d+)(?:\(\d+\))?/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'article-' . $r[1];
                return $placeholder('<a href="/references/eu-mdr#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 11. Standalone "Annex I/II/XIV" etc
            $text = preg_replace_callback('/\bAnnex\s+([IVXLC]+)\b/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'annex-' . strtolower($r[1]);
                return $placeholder('<a href="/references/eu-mdr#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // 12. Standalone "Clause N.N.N" (ISO 13485 assumed)
            $text = preg_replace_callback('/\bClause\s+([\d.]+)/', function ($r) use ($linkClass, $placeholder) {
                $anchor = 'clause-' . str_replace('.', '-', $r[1]);
                return $placeholder('<a href="/references/iso-13485#' . $anchor . '" class="' . $linkClass . '">' . $r[0] . '</a>');
            }, $text);

            // Restore placeholders
            return str_replace(array_keys($placeholders), array_values($placeholders), $text);
        }, $html);
    }
}
