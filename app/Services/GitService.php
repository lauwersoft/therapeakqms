<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Process;

class GitService
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/documents');
    }

    public function commitAndPush(User $user, string $message): void
    {
        $author = "{$user->name} <{$user->email}>";

        Process::path(base_path())
            ->run("git add qms/documents/");

        $result = Process::path(base_path())
            ->run(['git', 'commit', '--author', $author, '-m', $message]);

        if ($result->successful()) {
            Process::path(base_path())
                ->run("git push");
        }
    }

    public function moveFile(string $from, string $to, User $user): void
    {
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
        $fullPath = $this->basePath . '/' . $path;

        Process::path(base_path())
            ->run(['git', 'rm', $fullPath]);

        $this->commitAndPush($user, "Delete: {$path}");
    }
}
