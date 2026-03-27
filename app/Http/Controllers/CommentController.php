<?php

namespace App\Http\Controllers;

use App\Jobs\TrackUserActionJob;
use App\Models\UserActivity;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    private CommentService $comments;

    public function __construct(CommentService $comments)
    {
        $this->comments = $comments;
    }

    private function respond(Request $request, string $commentId, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'comment_id' => $commentId]);
        }

        $url = preg_replace('/#.*$/', '', url()->previous()) . '#comment-' . $commentId;
        return redirect($url)->with('success', $message);
    }

    /**
     * Return rendered comments HTML partial for a document.
     */
    public function partial(Request $request, string $docId)
    {
        $commentService = $this->comments;
        $docComments = $commentService->getVisibleComments($docId, $request->user()->role);

        // We need meta and content for the partial to work
        // Find the document to get its content for section extraction
        $basePath = base_path('qms/documents');
        $docIndex = \App\Services\DocumentMetadata::index($basePath);
        $meta = ['id' => $docId];
        $content = '';

        foreach ($docIndex as $path => $m) {
            if (($m['id'] ?? '') === $docId) {
                $meta = $m;
                if (\App\Services\DocumentMetadata::isMarkdown($path)) {
                    $raw = \Illuminate\Support\Facades\File::get($basePath . '/' . $path);
                    $parsed = \App\Services\DocumentMetadata::parse($raw);

                    $environment = new \League\CommonMark\Environment\Environment(['html_input' => 'strip', 'allow_unsafe_links' => false]);
                    $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
                    $environment->addExtension(new \League\CommonMark\Extension\Table\TableExtension());
                    $converter = new \League\CommonMark\MarkdownConverter($environment);
                    $content = $converter->convert($parsed['body'])->getContent();
                }
                break;
            }
        }

        $html = view('documents.partials.comments', [
            'meta' => $meta,
            'docComments' => $docComments,
            'content' => $content,
        ])->render();

        return response($html);
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

        if ($user->track_activity) TrackUserActionJob::dispatch($user->id, UserActivity::TYPE_COMMENT, $request->path(), $request->input('doc_id'), null, Str::limit($request->input('content'), 200), $request->ip());

        return $this->respond($request, $comment['id'], 'Comment added.');
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

        if ($user->track_activity) TrackUserActionJob::dispatch($user->id, UserActivity::TYPE_REPLY, $request->path(), $request->input('doc_id'), null, Str::limit($request->input('content'), 200), $request->ip());

        return $this->respond($request, $commentId, 'Reply added.');
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

        if ($user->track_activity) TrackUserActionJob::dispatch($user->id, UserActivity::TYPE_RESOLVE_COMMENT, $request->path(), $request->input('doc_id'), null, $request->input('note'), $request->ip());

        return $this->respond($request, $commentId, 'Comment resolved.');
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

        if ($request->user()->track_activity) TrackUserActionJob::dispatch($request->user()->id, UserActivity::TYPE_UNRESOLVE_COMMENT, $request->path(), $request->input('doc_id'), null, null, $request->ip());

        return $this->respond($request, $commentId, 'Comment reopened.');
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Comment deleted.', 'comment_id' => null]);
        }
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

        return $this->respond($request, $commentId, 'Reply deleted.');
    }
}
