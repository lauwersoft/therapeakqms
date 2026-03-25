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

        // Auditors can only create 'all' visibility comments
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

        return back()->with('success', 'Comment added.');
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

        $reply = $this->comments->addReply($request->input('doc_id'), $request->input('comment_id'), [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'content' => $request->input('content'),
        ]);

        if (! $reply) {
            return back()->withErrors(['Comment not found.']);
        }

        return back()->with('success', 'Reply added.');
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

        $this->comments->resolveComment(
            $request->input('doc_id'),
            $request->input('comment_id'),
            $user->id,
            $user->name,
            $request->input('note')
        );

        return back()->with('success', 'Comment resolved.');
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

        $this->comments->unresolveComment(
            $request->input('doc_id'),
            $request->input('comment_id')
        );

        return back()->with('success', 'Comment reopened.');
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

        return back()->with('success', 'Comment deleted.');
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

        $this->comments->deleteReply(
            $request->input('doc_id'),
            $request->input('comment_id'),
            $request->input('reply_id')
        );

        return back()->with('success', 'Reply deleted.');
    }
}
