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
     * Get last commit info (author + date) for a specific file.
     */
    public function getLastCommitInfo(string $path): ?array
    {
        $result = Process::path($this->base)
            ->run(['git', 'log', '-1', '--format=%an|%ae|%aI', '--', 'qms/documents/' . $path]);

        if ($result->successful() && trim($result->output())) {
            $parts = explode('|', trim($result->output()));
            if (count($parts) === 3) {
                return [
                    'name' => $parts[0],
                    'email' => $parts[1],
                    'date' => \Carbon\Carbon::parse($parts[2]),
                ];
            }
        }

        return null;
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
