<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class CommentService
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = base_path('qms/comments');

        try {
            if (! File::isDirectory($this->basePath)) {
                File::makeDirectory($this->basePath, 0775, true);
            }
        } catch (\Throwable $e) {
            // Directory might already exist or permissions may prevent creation
            // Comments will gracefully return empty arrays if dir doesn't exist
        }
    }

    /**
     * Get all comments for a document by its ID.
     */
    public function getComments(string $docId): array
    {
        $filePath = $this->filePath($docId);

        if (! File::exists($filePath)) {
            return [];
        }

        try {
            $data = json_decode(File::get($filePath), true);
            return is_array($data) ? $data : [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Get comments filtered by visibility for a specific user role.
     */
    public function getVisibleComments(string $docId, string $role): array
    {
        $comments = $this->getComments($docId);

        if ($role === 'admin' || $role === 'editor') {
            return $comments;
        }

        // Auditors can only see 'all' visibility comments
        return array_values(array_filter($comments, fn ($c) => ($c['visibility'] ?? 'internal') === 'all'));
    }

    /**
     * Add a comment to a document.
     */
    public function addComment(string $docId, array $comment): array
    {
        $comment = array_merge([
            'id' => 'c_' . time() . '_' . mt_rand(1000, 9999),
            'section' => null,
            'type' => 'observation',
            'visibility' => 'internal',
            'content' => '',
            'resolved' => false,
            'resolved_by' => null,
            'resolved_note' => null,
            'resolved_at' => null,
            'replies' => [],
            'created_at' => now()->toIso8601String(),
        ], $comment);

        $this->withLock($docId, function (&$comments) use ($comment) {
            $comments[] = $comment;
        });

        $this->backgroundPush($docId, 'Comment on ' . $docId);

        return $comment;
    }

    /**
     * Add a reply to a comment.
     */
    public function addReply(string $docId, string $commentId, array $reply): ?array
    {
        $reply = array_merge([
            'id' => 'r_' . time() . '_' . mt_rand(1000, 9999),
            'content' => '',
            'created_at' => now()->toIso8601String(),
        ], $reply);

        $found = false;
        $this->withLock($docId, function (&$comments) use ($commentId, $reply, &$found) {
            foreach ($comments as &$comment) {
                if ($comment['id'] === $commentId) {
                    $comment['replies'][] = $reply;
                    $found = true;
                    break;
                }
            }
        });

        if ($found) {
            $this->backgroundPush($docId, 'Reply on ' . $docId);
            return $reply;
        }

        return null;
    }

    /**
     * Resolve a comment.
     */
    public function resolveComment(string $docId, string $commentId, int $userId, string $userName, ?string $note = null): bool
    {
        $found = false;
        $this->withLock($docId, function (&$comments) use ($commentId, $userId, $userName, $note, &$found) {
            foreach ($comments as &$comment) {
                if ($comment['id'] === $commentId) {
                    $comment['resolved'] = true;
                    $comment['resolved_by'] = $userName;
                    $comment['resolved_note'] = $note;
                    $comment['resolved_at'] = now()->toIso8601String();
                    $found = true;
                    break;
                }
            }
        });

        if ($found) {
            $this->backgroundPush($docId, 'Resolved comment on ' . $docId);
        }

        return $found;
    }

    /**
     * Unresolve a comment (reopen).
     */
    public function unresolveComment(string $docId, string $commentId): bool
    {
        $found = false;
        $this->withLock($docId, function (&$comments) use ($commentId, &$found) {
            foreach ($comments as &$comment) {
                if ($comment['id'] === $commentId) {
                    $comment['resolved'] = false;
                    $comment['resolved_by'] = null;
                    $comment['resolved_note'] = null;
                    $comment['resolved_at'] = null;
                    $found = true;
                    break;
                }
            }
        });

        if ($found) {
            $this->backgroundPush($docId, 'Reopened comment on ' . $docId);
        }

        return $found;
    }

    /**
     * Delete a comment.
     */
    public function deleteComment(string $docId, string $commentId): bool
    {
        $found = false;
        $this->withLock($docId, function (&$comments) use ($commentId, &$found) {
            $original = count($comments);
            $comments = array_values(array_filter($comments, fn ($c) => $c['id'] !== $commentId));
            $found = count($comments) < $original;
        });

        if ($found) {
            $this->backgroundPush($docId, 'Deleted comment on ' . $docId);
        }

        return $found;
    }

    /**
     * Delete a reply from a comment.
     */
    public function deleteReply(string $docId, string $commentId, string $replyId): bool
    {
        $found = false;
        $this->withLock($docId, function (&$comments) use ($commentId, $replyId, &$found) {
            foreach ($comments as &$comment) {
                if ($comment['id'] === $commentId) {
                    $original = count($comment['replies'] ?? []);
                    $comment['replies'] = array_values(array_filter($comment['replies'] ?? [], fn ($r) => $r['id'] !== $replyId));
                    $found = count($comment['replies']) < $original;
                    break;
                }
            }
        });

        if ($found) {
            $this->backgroundPush($docId, 'Deleted reply on ' . $docId);
        }

        return $found;
    }

    /**
     * Count unresolved comments of type 'required_change' for a document.
     */
    public function unresolvedRequiredChanges(string $docId): int
    {
        $comments = $this->getComments($docId);
        return count(array_filter($comments, fn ($c) => ($c['type'] ?? '') === 'required_change' && ! ($c['resolved'] ?? false)));
    }

    /**
     * Count all unresolved comments across all documents.
     */
    public function allUnresolvedCount(?string $role = 'admin'): int
    {
        $count = 0;
        $files = File::glob($this->basePath . '/*.json');

        foreach ($files as $file) {
            try {
                $data = json_decode(File::get($file), true);
                if (! is_array($data)) {
                    continue;
                }

                foreach ($data as $comment) {
                    if ($comment['resolved'] ?? false) {
                        continue;
                    }
                    if ($role === 'auditor' && ($comment['visibility'] ?? 'internal') !== 'all') {
                        continue;
                    }
                    $count++;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $count;
    }

    /**
     * Get summary of comments per document (for dashboard).
     */
    public function summary(): array
    {
        $result = [];
        $files = File::glob($this->basePath . '/*.json');

        foreach ($files as $file) {
            try {
                $data = json_decode(File::get($file), true);
                if (! is_array($data) || empty($data)) {
                    continue;
                }

                $docId = pathinfo($file, PATHINFO_FILENAME);
                $unresolved = count(array_filter($data, fn ($c) => ! ($c['resolved'] ?? false)));
                $total = count($data);

                if ($total > 0) {
                    $result[$docId] = [
                        'total' => $total,
                        'unresolved' => $unresolved,
                    ];
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $result;
    }

    /**
     * Get recent unresolved comments across all documents.
     */
    public function recentUnresolved(int $limit = 5, ?string $role = 'admin'): array
    {
        $all = [];
        $files = File::glob($this->basePath . '/*.json');

        foreach ($files as $file) {
            try {
                $data = json_decode(File::get($file), true);
                if (! is_array($data)) {
                    continue;
                }

                $docId = pathinfo($file, PATHINFO_FILENAME);
                foreach ($data as $comment) {
                    if ($comment['resolved'] ?? false) {
                        continue;
                    }
                    if ($role === 'auditor' && ($comment['visibility'] ?? 'internal') !== 'all') {
                        continue;
                    }
                    $comment['_doc_id'] = $docId;
                    $all[] = $comment;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        // Sort by created_at descending
        usort($all, fn ($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

        return array_slice($all, 0, $limit);
    }

    /**
     * Read/modify comments with file locking.
     */
    private function withLock(string $docId, callable $callback): void
    {
        $filePath = $this->filePath($docId);
        $dir = dirname($filePath);

        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0775, true);
        }

        try {
            $handle = fopen($filePath, 'c+');
        } catch (\Throwable $e) {
            $handle = false;
        }
        if (! $handle) {
            return;
        }

        flock($handle, LOCK_EX);

        $content = stream_get_contents($handle);
        $comments = [];

        if ($content) {
            try {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $comments = $decoded;
                }
            } catch (\Throwable $e) {
                // Start fresh if JSON is corrupted
            }
        }

        $callback($comments);

        fseek($handle, 0);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        fflush($handle);
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    /**
     * Background git push for comment files.
     */
    private function backgroundPush(string $docId, string $message): void
    {
        $base = base_path();
        $cmd = "cd {$base} && git pull --no-rebase 2>/dev/null; git add qms/comments/ && git commit -m \"{$message}\" --author=\"QMS System <qms@system>\" && git push";

        try {
            Process::start($cmd);
        } catch (\Throwable $e) {
            // Silent fail — file is on disk, will be pushed with next commit
        }
    }

    private function filePath(string $docId): string
    {
        // Sanitize doc ID for filename safety
        $safe = preg_replace('/[^A-Za-z0-9\-]/', '', $docId);
        return $this->basePath . '/' . $safe . '.json';
    }
}
