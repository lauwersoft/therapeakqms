<?php

namespace App\Services;

use App\Models\DocumentChange;
use App\Models\User;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class GitService
{
    private string $base;

    public function __construct()
    {
        $this->base = base_path();
    }

    public function publish(User $user, string $message): void
    {
        // Pull latest to avoid conflicts
        $pull = Process::path($this->base)->run("git pull --no-rebase");

        if (! $pull->successful() && str_contains($pull->errorOutput(), 'CONFLICT')) {
            Process::path($this->base)->run("git merge --abort");
            throw new RuntimeException('A conflict was detected with remote changes. Please contact an administrator to resolve this before publishing.');
        }

        // Stage all document changes
        Process::path($this->base)->run("git add qms/documents/");

        // Check if there's anything to commit
        $status = Process::path($this->base)->run("git diff --cached --quiet");
        if ($status->successful()) {
            // Nothing staged — clean up changes table anyway
            DocumentChange::truncate();
            return;
        }

        // Build commit message with change details and authors
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

            // Add co-authors
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

        // Clear changes table after successful publish
        DocumentChange::truncate();
    }

    public function discard(string $path): void
    {
        Process::path($this->base)
            ->run(['git', 'checkout', '--', 'qms/documents/' . $path]);

        // Remove change records for this path
        DocumentChange::where('path', $path)->delete();
    }

    public function discardAll(): void
    {
        Process::path($this->base)
            ->run("git checkout -- qms/documents/");

        // Restore any deleted files
        $lsFiles = Process::path($this->base)
            ->run("git diff --name-only --diff-filter=D HEAD -- qms/documents/");

        if ($lsFiles->successful() && trim($lsFiles->output())) {
            foreach (explode("\n", trim($lsFiles->output())) as $file) {
                Process::path($this->base)->run(['git', 'checkout', 'HEAD', '--', $file]);
            }
        }

        // Remove any untracked files in documents
        Process::path($this->base)
            ->run("git clean -fd qms/documents/");

        DocumentChange::truncate();
    }

    public function getUnpublishedDiff(): string
    {
        // Staged + unstaged changes
        $diff = Process::path($this->base)
            ->run("git diff HEAD -- qms/documents/");

        // Also show untracked files
        $untracked = Process::path($this->base)
            ->run("git ls-files --others --exclude-standard qms/documents/");

        $output = $diff->output();

        if (trim($untracked->output())) {
            foreach (explode("\n", trim($untracked->output())) as $file) {
                $output .= "\n+++ New file: {$file}\n";
            }
        }

        return $output;
    }

    public function getChangedFiles(): array
    {
        $files = [];

        // Modified and deleted
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

        // Untracked (new files)
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

    public function getFileDiff(string $path): string
    {
        $result = Process::path($this->base)
            ->run(['git', 'diff', 'HEAD', '--', 'qms/documents/' . $path]);

        return $result->output();
    }
}
