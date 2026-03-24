<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use App\Services\DocumentMetadata;
use App\Services\GitService;

class DashboardController extends Controller
{
    public function index(GitService $git)
    {
        $basePath = base_path('qms/documents');
        $docIndex = DocumentMetadata::index($basePath);
        $recentCommits = $git->getHistory(5);

        // Enrich with document metadata
        foreach ($recentCommits as &$commit) {
            foreach ($commit['files'] as &$file) {
                $meta = $docIndex[$file['path']] ?? null;
                $file['doc_id'] = $meta['id'] ?? null;
                $file['doc_title'] = $meta['title'] ?? ucwords(str_replace(['-', '_'], ' ', str_replace('.md', '', basename($file['path']))));
                $file['doc_type'] = $meta['type'] ?? null;
            }
        }

        // Pending changes
        $pendingCount = \App\Models\DocumentChange::count();

        // Stats
        $totalDocs = count($docIndex);
        $draftCount = count(array_filter($docIndex, fn ($m) => ($m['status'] ?? '') === 'draft'));
        $approvedCount = count(array_filter($docIndex, fn ($m) => ($m['status'] ?? '') === 'approved'));
        $inReviewCount = count(array_filter($docIndex, fn ($m) => ($m['status'] ?? '') === 'in_review'));

        // Doc list for search
        $docList = [];
        foreach ($docIndex as $path => $meta) {
            $docList[] = [
                'path' => str_replace('.md', '', $path),
                'doc_id' => $meta['id'] ?? null,
                'title' => $meta['title'] ?? ucwords(str_replace(['-', '_'], ' ', str_replace('.md', '', basename($path)))),
                'type' => $meta['type'] ?? null,
                'status' => $meta['status'] ?? 'draft',
            ];
        }

        // Comments
        $commentService = app(CommentService::class);
        $unresolvedComments = $commentService->allUnresolvedCount(auth()->user()->role);

        return view('dashboard', [
            'recentCommits' => $recentCommits,
            'totalDocs' => $totalDocs,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'inReviewCount' => $inReviewCount,
            'docList' => $docList,
            'pendingCount' => $pendingCount,
            'unresolvedComments' => $unresolvedComments,
        ]);
    }
}
