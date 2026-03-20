<?php

namespace App\Http\Controllers;

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
            }
        }

        // Stats
        $totalDocs = count($docIndex);
        $draftCount = count(array_filter($docIndex, fn ($m) => ($m['status'] ?? '') === 'draft'));
        $approvedCount = count(array_filter($docIndex, fn ($m) => ($m['status'] ?? '') === 'approved'));
        $inReviewCount = count(array_filter($docIndex, fn ($m) => ($m['status'] ?? '') === 'in_review'));

        return view('dashboard', [
            'recentCommits' => $recentCommits,
            'totalDocs' => $totalDocs,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'inReviewCount' => $inReviewCount,
        ]);
    }
}
