<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class QmsNotificationService
{
    /**
     * Notify users about a new comment on a document.
     */
    public static function commentAdded(string $docId, string $docTitle, string $commenterName, string $commentType, string $commentContent, string $docPath): void
    {
        $recipients = User::where('approved', true)
            ->where('name', '!=', $commenterName)
            ->get()
            ->filter(fn($u) => $u->wantsNotification('comments'));

        foreach ($recipients as $user) {
            Mail::send('emails.comment-added', [
                'user' => $user,
                'docId' => $docId,
                'docTitle' => $docTitle,
                'commenterName' => $commenterName,
                'commentType' => $commentType,
                'commentContent' => $commentContent,
                'docUrl' => url('/qms/' . preg_replace('/(\.\w+)+$/', '', $docPath)) . '#comments-section',
            ], function ($message) use ($user, $docId, $commentType) {
                $subject = $commentType === 'required_change'
                    ? "[Action Required] Required change on {$docId}"
                    : "New comment on {$docId}";
                $message->to($user->email, $user->name)->subject($subject);
            });
        }
    }

    /**
     * Notify users about a reply to a comment.
     */
    public static function replyAdded(string $docId, string $docTitle, string $replierName, string $replyContent, string $docPath, string $commentId): void
    {
        $recipients = User::where('approved', true)
            ->where('name', '!=', $replierName)
            ->get()
            ->filter(fn($u) => $u->wantsNotification('comments'));

        foreach ($recipients as $user) {
            Mail::send('emails.reply-added', [
                'user' => $user,
                'docId' => $docId,
                'docTitle' => $docTitle,
                'replierName' => $replierName,
                'replyContent' => $replyContent,
                'docUrl' => url('/qms/' . preg_replace('/(\.\w+)+$/', '', $docPath)) . '#comment-' . $commentId,
            ], function ($message) use ($user, $docId) {
                $message->to($user->email, $user->name)->subject("Reply on {$docId}");
            });
        }
    }

    /**
     * Notify users about published document changes.
     */
    public static function documentsPublished(string $publisherName, array $changedFiles): void
    {
        $recipients = User::where('approved', true)
            ->where('name', '!=', $publisherName)
            ->get()
            ->filter(fn($u) => $u->wantsNotification('publications'));

        foreach ($recipients as $user) {
            Mail::send('emails.documents-published', [
                'user' => $user,
                'publisherName' => $publisherName,
                'changedFiles' => $changedFiles,
                'qmsUrl' => url('/qms'),
            ], function ($message) use ($user, $changedFiles) {
                $count = count($changedFiles);
                $message->to($user->email, $user->name)
                    ->subject("QMS Updated — {$count} " . ($count === 1 ? 'document' : 'documents') . " published");
            });
        }
    }
}
