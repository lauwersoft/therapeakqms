<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private CommentService $comments;

    public function __construct(CommentService $comments)
    {
        $this->comments = $comments;
    }

    private function backToComment(string $commentId, string $message)
    {
        $url = url()->previous();
        $url = preg_replace('/#.*$/', '', $url);
        $url .= '#comment-' . $commentId;
        return redirect($url)->with('success', $message);
    }

    /**
     * Store a new comment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doc_id' => 'required|string|max:20',
            'section' => 'nullable|string|max:255',
            'type' => 'required|string|in:observation,required_change,question',
            'visibility' => 'required|string|in:internal,all',
            'content' => 'required|string|max:5000',
        ]);

        $user = $request->user();

        $visibility = $request->input('visibility');
        if ($user->isAuditor()) {
            $visibility = 'all';
        }

        $comment = $this->comments->addComment($request->input('doc_id'), [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'section' => $request->input('section'),
            'type' => $request->input('type'),
            'visibility' => $visibility,
            'content' => $request->input('content'),
        ]);

        return $this->backToComment($comment['id'], 'Comment added.');
    }

    /**
     * Store a reply to a comment.
     */
    public function reply(Request $request)
    {
        $request->validate([
            'doc_id' => 'required|string|max:20',
            'comment_id' => 'required|string|max:50',
            'content' => 'required|string|max:5000',
        ]);

        $user = $request->user();
        $commentId = $request->input('comment_id');

        $reply = $this->comments->addReply($request->input('doc_id'), $commentId, [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'content' => $request->input('content'),
        ]);

        if (! $reply) {
            return back()->withErrors(['Comment not found.']);
        }

        return $this->backToComment($commentId, 'Reply added.');
    }

    /**
     * Resolve a comment.
     */
    public function resolve(Request $request)
    {
        $request->validate([
            'doc_id' => 'required|string|max:20',
            'comment_id' => 'required|string|max:50',
            'note' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();
        $commentId = $request->input('comment_id');

        $this->comments->resolveComment(
            $request->input('doc_id'),
            $commentId,
            $user->id,
            $user->name,
            $request->input('note')
        );

        return $this->backToComment($commentId, 'Comment resolved.');
    }

    /**
     * Reopen a resolved comment.
     */
    public function unresolve(Request $request)
    {
        $request->validate([
            'doc_id' => 'required|string|max:20',
            'comment_id' => 'required|string|max:50',
        ]);

        $commentId = $request->input('comment_id');

        $this->comments->unresolveComment(
            $request->input('doc_id'),
            $commentId
        );

        return $this->backToComment($commentId, 'Comment reopened.');
    }

    /**
     * Delete a comment (admin only).
     */
    public function destroy(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'doc_id' => 'required|string|max:20',
            'comment_id' => 'required|string|max:50',
        ]);

        $this->comments->deleteComment(
            $request->input('doc_id'),
            $request->input('comment_id')
        );

        $url = preg_replace('/#.*$/', '', url()->previous()) . '#comments-section';
        return redirect($url)->with('success', 'Comment deleted.');
    }

    /**
     * Delete a reply (admin only).
     */
    public function destroyReply(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'doc_id' => 'required|string|max:20',
            'comment_id' => 'required|string|max:50',
            'reply_id' => 'required|string|max:50',
        ]);

        $commentId = $request->input('comment_id');

        $this->comments->deleteReply(
            $request->input('doc_id'),
            $commentId,
            $request->input('reply_id')
        );

        return $this->backToComment($commentId, 'Reply deleted.');
    }
}
