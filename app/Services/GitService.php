<?php

namespace App\Services;

use App\Models\DocumentChange;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class GitService
{
    private string $base;
    private string $docsPath;

    public function __construct()
    {
        $this->base = base_path();
        $this->docsPath = base_path('qms/documents');
    }

    public function publish(User $user, string $message): void
    {
        $pull = Process::path($this->base)->run("git pull --no-rebase");

        if (! $pull->successful() && str_contains($pull->errorOutput(), 'CONFLICT')) {
            Process::path($this->base)->run("git merge --abort");
            throw new RuntimeException('A conflict was detected with remote changes. Please contact an administrator to resolve this before publishing.');
        }

        // Stage all document changes (including deletes)
        Process::path($this->base)->run("git add -A qms/documents/");

        $status = Process::path($this->base)->run("git diff --cached --quiet");
        if ($status->successful()) {
            DocumentChange::truncate();
            return;
        }

        $changes = DocumentChange::with('user')->oldest()->get();
        $authors = $changes->pluck('user')->unique('id');

        $body = $message;
        if ($changes->isNotEmpty()) {
            $body .= "\n\nChanges:\n";
            foreach ($changes as $change) {
                $detail = match ($change->action) {
                    'edit' => "- Edit: {$change->path} (by {$change->user->name})",
                    'create' => "- Create: {$change->path} (by {$change->user->name})",
                    'delete' => "- Delete: {$change->path} (by {$change->user->name})",
                    'move' => "- Move: " . ($change->details['old_path'] ?? '?') . " → {$change->path} (by {$change->user->name})",
                    'rename' => "- Rename: " . ($change->details['old_path'] ?? '?') . " → {$change->path} (by {$change->user->name})",
                    default => "- {$change->action}: {$change->path} (by {$change->user->name})",
                };
                $body .= $detail . "\n";
            }

            foreach ($authors as $author) {
                if ($author->id !== $user->id) {
                    $body .= "\nCo-Authored-By: {$author->name} <{$author->email}>";
                }
            }
        }

        $authorString = "{$user->name} <{$user->email}>";
        $commit = Process::path($this->base)
            ->run(['git', 'commit', '--author', $authorString, '-m', $body]);

        if (! $commit->successful()) {
            throw new RuntimeException('Failed to create commit: ' . $commit->errorOutput());
        }

        $push = Process::path($this->base)->run("git push");
        if (! $push->successful()) {
            throw new RuntimeException('Failed to push to remote: ' . $push->errorOutput());
        }

        DocumentChange::truncate();
    }

    public function discard(string $path): void
    {
        $fullPath = $this->docsPath . '/' . $path;

        // Check if this is a new (untracked) file — just delete it
        $lsFiles = Process::path($this->base)
            ->run(['git', 'ls-files', '--error-unmatch', 'qms/documents/' . $path]);

        if (! $lsFiles->successful()) {
            // Untracked file — delete it
            if (File::exists($fullPath)) {
                unlink($fullPath);
            }
        } else {
            // Tracked file — restore from HEAD
            Process::path($this->base)
                ->run(['git', 'checkout', 'HEAD', '--', 'qms/documents/' . $path]);
        }

        DocumentChange::where('path', $path)->delete();
    }

    public function discardAll(): void
    {
        // Restore all tracked files
        Process::path($this->base)
            ->run("git checkout HEAD -- qms/documents/");

        // Remove untracked files
        Process::path($this->base)
            ->run("git clean -fd qms/documents/");

        DocumentChange::truncate();
    }

    /**
     * Get all changed files, enriched with activity log data for moves/renames.
     */
    public function getChangedFiles(): array
    {
        $gitChanges = $this->getRawGitChanges();
        $logEntries = DocumentChange::oldest()->get();

        // Identify moves/renames from activity log
        $moves = [];
        foreach ($logEntries as $entry) {
            if (in_array($entry->action, ['move', 'rename']) && isset($entry->details['old_path'])) {
                $moves[$entry->details['old_path']] = [
                    'new_path' => $entry->path,
                    'action' => $entry->action,
                ];
            }
        }

        // Merge: match deleted old_path + new new_path into a single move/rename entry
        $result = [];
        $handledNew = [];

        foreach ($gitChanges as $path => $status) {
            if ($status === 'deleted' && isset($moves[$path])) {
                $move = $moves[$path];
                $newPath = $move['new_path'];

                // This delete is part of a move/rename
                $result[$newPath] = [
                    'status' => $move['action'], // 'move' or 'rename'
                    'old_path' => $path,
                ];
                $handledNew[$newPath] = true;
                continue;
            }

            if (isset($handledNew[$path])) {
                continue; // Already handled as part of a move
            }

            $result[$path] = ['status' => $status];
        }

        return $result;
    }

    /**
     * Get raw git changes (modified, deleted, new).
     */
    private function getRawGitChanges(): array
    {
        $files = [];

        $result = Process::path($this->base)
            ->run("git diff --name-status HEAD -- qms/documents/");

        if ($result->successful() && trim($result->output())) {
            foreach (explode("\n", trim($result->output())) as $line) {
                $parts = preg_split('/\s+/', $line, 2);
                if (count($parts) === 2) {
                    $status = match ($parts[0]) {
                        'M' => 'modified',
                        'D' => 'deleted',
                        'A' => 'added',
                        default => $parts[0],
                    };
                    $path = str_replace('qms/documents/', '', $parts[1]);
                    $files[$path] = $status;
                }
            }
        }

        $untracked = Process::path($this->base)
            ->run("git ls-files --others --exclude-standard qms/documents/");

        if ($untracked->successful() && trim($untracked->output())) {
            foreach (explode("\n", trim($untracked->output())) as $file) {
                $path = str_replace('qms/documents/', '', $file);
                if ($path && $path !== '.gitkeep' && ! str_ends_with($path, '/.gitkeep')) {
                    $files[$path] = 'new';
                }
            }
        }

        return $files;
    }

    /**
     * Get commit history for qms/documents.
     */
    public function getHistory(int $limit = 50, int $offset = 0): array
    {
        // Fetch more than needed to account for filtered commits
        $fetchLimit = $limit + $offset + 50;
        $result = Process::path($this->base)
            ->run(['git', 'log', '--no-merges', '--after=2026-03-25', '--format=%H|%an|%ae|%aI|%s', '-' . $fetchLimit, '--', 'qms/documents/']);

        $commits = [];
        $skipped = 0;

        if ($result->successful() && trim($result->output())) {
            foreach (explode("\n", trim($result->output())) as $line) {
                $parts = explode('|', $line, 5);
                if (count($parts) !== 5) continue;

                $hash = $parts[0];
                $message = $parts[4];

                // Skip noise commits
                if ($this->isNoiseCommit($message)) continue;

                // Get changed files for this commit
                $filesResult = Process::path($this->base)
                    ->run(['git', 'diff-tree', '--no-commit-id', '--name-status', '-r', $hash, '--', 'qms/documents/']);

                $files = [];
                if ($filesResult->successful() && trim($filesResult->output())) {
                    foreach (explode("\n", trim($filesResult->output())) as $fileLine) {
                        $fileParts = preg_split('/\s+/', $fileLine, 2);
                        if (count($fileParts) === 2) {
                            $filePath = str_replace('qms/documents/', '', $fileParts[1]);

                            // Skip .gitkeep files
                            if (str_ends_with($filePath, '.gitkeep')) continue;

                            $status = match ($fileParts[0]) {
                                'A' => 'added',
                                'M' => 'modified',
                                'D' => 'deleted',
                                default => $fileParts[0],
                            };
                            $files[] = [
                                'status' => $status,
                                'path' => $filePath,
                            ];
                        }
                    }
                }

                // Skip commits that only touched .gitkeep files
                if (empty($files)) continue;

                // Handle pagination
                if ($skipped < $offset) {
                    $skipped++;
                    continue;
                }

                $commits[] = [
                    'hash' => $hash,
                    'short_hash' => substr($hash, 0, 7),
                    'author' => $parts[1],
                    'email' => $parts[2],
                    'date' => \Carbon\Carbon::parse($parts[3]),
                    'message' => $message,
                    'files' => $files,
                ];

                if (count($commits) >= $limit) break;
            }
        }

        return $commits;
    }

    /**
     * Check if a commit message is noise (WIP, merge, etc.)
     */
    private function isNoiseCommit(string $message): bool
    {
        $noisePatterns = [
            '/^WIP$/i',
            '/^wip\b/i',
            '/^Merge branch/i',
            '/^Merge remote/i',
            '/^Initial (commit|Laravel)/i',
            '/^Update package-lock/i',
        ];

        foreach ($noisePatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a single commit's details including per-file diffs.
     */
    public function getCommitDetail(string $hash): ?array
    {
        // Get commit info
        $result = Process::path($this->base)
            ->run(['git', 'log', '-1', '--format=%H|%an|%ae|%aI|%B', $hash]);

        if (! $result->successful() || ! trim($result->output())) {
            return null;
        }

        $parts = explode('|', trim($result->output()), 5);
        if (count($parts) !== 5) return null;

        // Get changed files
        $filesResult = Process::path($this->base)
            ->run(['git', 'diff-tree', '--no-commit-id', '--name-status', '-r', $hash, '--', 'qms/documents/']);

        $files = [];
        if ($filesResult->successful() && trim($filesResult->output())) {
            foreach (explode("\n", trim($filesResult->output())) as $fileLine) {
                $fileParts = preg_split('/\s+/', $fileLine, 2);
                if (count($fileParts) !== 2) continue;

                $filePath = str_replace('qms/documents/', '', $fileParts[1]);
                if (str_ends_with($filePath, '.gitkeep')) continue;

                $status = match ($fileParts[0]) {
                    'A' => 'added',
                    'M' => 'modified',
                    'D' => 'deleted',
                    default => $fileParts[0],
                };

                $diff = '';

                if ($status === 'added') {
                    // New file: get clean body content
                    $showResult = Process::path($this->base)
                        ->run(['git', 'show', $hash . ':qms/documents/' . $filePath]);
                    if ($showResult->successful()) {
                        $parsed = \App\Services\DocumentMetadata::parse($showResult->output());
                        $diff = $parsed['body'];
                    }
                } elseif ($status === 'deleted') {
                    // Deleted file: get old body content
                    $showResult = Process::path($this->base)
                        ->run(['git', 'show', $hash . '~1:qms/documents/' . $filePath]);
                    if ($showResult->successful()) {
                        $parsed = \App\Services\DocumentMetadata::parse($showResult->output());
                        $diff = $parsed['body'];
                    }
                } else {
                    // Modified: get standard diff
                    $diffResult = Process::path($this->base)
                        ->run(['git', 'diff', '--no-color', $hash . '~1', $hash, '--', 'qms/documents/' . $filePath]);
                    $diff = $diffResult->output();
                }

                // Get old and new metadata for modified files
                $metaChanges = [];
                if ($status === 'modified') {
                    $oldContent = Process::path($this->base)
                        ->run(['git', 'show', $hash . '~1:qms/documents/' . $filePath]);
                    $newContent = Process::path($this->base)
                        ->run(['git', 'show', $hash . ':qms/documents/' . $filePath]);

                    if ($oldContent->successful() && $newContent->successful()) {
                        $oldMeta = \App\Services\DocumentMetadata::parse($oldContent->output())['meta'];
                        $newMeta = \App\Services\DocumentMetadata::parse($newContent->output())['meta'];
                        $metaChanges = \App\Services\DocumentMetadata::diffMeta($oldMeta, $newMeta);
                    }
                }

                $files[] = [
                    'status' => $status,
                    'path' => $filePath,
                    'diff' => $diff,
                    'metaChanges' => $metaChanges,
                ];
            }
        }

        return [
            'hash' => $parts[0],
            'short_hash' => substr($parts[0], 0, 7),
            'author' => $parts[1],
            'email' => $parts[2],
            'date' => \Carbon\Carbon::parse($parts[3]),
            'message' => trim($parts[4]),
            'files' => $files,
        ];
    }

    /**
     * Get total number of meaningful commits for qms/documents.
     */
    public function getHistoryCount(): int
    {
        $result = Process::path($this->base)
            ->run(['git', 'log', '--no-merges', '--after=2026-03-25', '--oneline', '--', 'qms/documents/']);

        if (! $result->successful() || ! trim($result->output())) return 0;

        $count = 0;
        foreach (explode("\n", trim($result->output())) as $line) {
            $parts = explode(' ', $line, 2);
            $message = $parts[1] ?? '';
            if (! $this->isNoiseCommit($message)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get last commit info (author + date) for a specific file.
     */
    public function getLastCommitInfo(string $path): ?array
    {
        $result = Process::path($this->base)
            ->run(['git', 'log', '-1', '--format=%H|%an|%ae|%aI', '--', 'qms/documents/' . $path]);

        if ($result->successful() && trim($result->output())) {
            $parts = explode('|', trim($result->output()));
            if (count($parts) === 4) {
                return [
                    'hash' => $parts[0],
                    'name' => $parts[1],
                    'email' => $parts[2],
                    'date' => \Carbon\Carbon::parse($parts[3]),
                ];
            }
        }

        return null;
    }

    /**
     * Get commit history for a specific file.
     */
    public function getFileHistory(string $path, int $limit = 20): array
    {
        $result = Process::path($this->base)
            ->run(['git', 'log', '--no-merges', '--after=2026-03-25', '--format=%H|%an|%ae|%aI|%s', '-' . $limit, '--', 'qms/documents/' . $path]);

        $commits = [];
        if ($result->successful() && trim($result->output())) {
            foreach (explode("\n", trim($result->output())) as $line) {
                $parts = explode('|', $line, 5);
                if (count($parts) !== 5) continue;
                if ($this->isNoiseCommit($parts[4])) continue;

                $commits[] = [
                    'hash' => $parts[0],
                    'short_hash' => substr($parts[0], 0, 7),
                    'author' => $parts[1],
                    'date' => \Carbon\Carbon::parse($parts[3]),
                    'message' => $parts[4],
                ];
            }
        }

        return $commits;
    }

    public function getFileDiff(string $path): string
    {
        $result = Process::path($this->base)
            ->run(['git', 'diff', 'HEAD', '--no-color', '--', 'qms/documents/' . $path]);

        $output = $result->output();
        if (empty($output)) {
            $output = $result->errorOutput();
        }

        return $output;
    }

    /**
     * Get the content of a file as it was in the last published (committed) version.
     */
    public function getOriginalContent(string $path): ?string
    {
        $result = Process::path($this->base)
            ->run(['git', 'show', 'HEAD:qms/documents/' . $path]);

        if ($result->successful()) {
            return $result->output();
        }

        return null;
    }
}
