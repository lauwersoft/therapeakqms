<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class GitService
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/documents');
    }

    public function commitAndPush(User $user, string $message): void
    {
        $this->pull();

        $author = "{$user->name} <{$user->email}>";

        Process::path(base_path())
            ->run("git add qms/documents/");

        $result = Process::path(base_path())
            ->run(['git', 'commit', '--author', $author, '-m', $message]);

        if ($result->successful()) {
            $push = Process::path(base_path())
                ->run("git push");

            if (! $push->successful()) {
                throw new RuntimeException('Failed to push changes. Please try again.');
            }
        }
    }

    public function moveFile(string $from, string $to, User $user): void
    {
        $this->pull();

        $fullFrom = $this->basePath . '/' . $from;
        $fullTo = $this->basePath . '/' . $to;

        $toDir = dirname($fullTo);
        if (! is_dir($toDir)) {
            mkdir($toDir, 0755, true);
        }

        Process::path(base_path())
            ->run(['git', 'mv', $fullFrom, $fullTo]);

        $this->commitAndPush($user, "Move: {$from} → {$to}");
    }

    public function deleteFile(string $path, User $user): void
    {
        $this->pull();

        $fullPath = $this->basePath . '/' . $path;

        Process::path(base_path())
            ->run(['git', 'rm', $fullPath]);

        $this->commitAndPush($user, "Delete: {$path}");
    }

    private function pull(): void
    {
        $result = Process::path(base_path())
            ->run("git pull --no-rebase");

        if (! $result->successful() && str_contains($result->errorOutput(), 'CONFLICT')) {
            // Abort the merge so we don't leave the repo in a broken state
            Process::path(base_path())
                ->run("git merge --abort");

            throw new RuntimeException('A conflict was detected with remote changes. Please contact an administrator.');
        }
    }
}
