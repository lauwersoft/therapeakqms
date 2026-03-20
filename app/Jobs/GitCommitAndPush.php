<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class GitCommitAndPush implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private string $authorName,
        private string $authorEmail,
        private string $message,
    ) {}

    public function handle(): void
    {
        $base = base_path();

        // Pull first
        $pull = Process::path($base)->run("git pull --no-rebase");
        if (! $pull->successful() && str_contains($pull->errorOutput(), 'CONFLICT')) {
            Process::path($base)->run("git merge --abort");
            Log::error("Git conflict during pull: {$pull->errorOutput()}");
            return;
        }

        // Stage
        Process::path($base)->run("git add qms/documents/");

        // Commit
        $author = "{$this->authorName} <{$this->authorEmail}>";
        $commit = Process::path($base)
            ->run(['git', 'commit', '--author', $author, '-m', $this->message]);

        if (! $commit->successful()) {
            return;
        }

        // Push
        $push = Process::path($base)->run("git push");
        if (! $push->successful()) {
            Log::error("Git push failed: {$push->errorOutput()}");
        }
    }
}
