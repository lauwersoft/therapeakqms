<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CommentService;
use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminGuideController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()->isAdmin()) {
            abort(403);
        }

        // Gather QMS health stats
        $basePath = base_path('qms/documents');
        $docIndex = DocumentMetadata::index($basePath);

        $totalDocs = count($docIndex);
        $draftCount = count(array_filter($docIndex, fn($m) => ($m['status'] ?? '') === 'draft'));
        $approvedCount = count(array_filter($docIndex, fn($m) => ($m['status'] ?? '') === 'approved'));
        $inReviewCount = count(array_filter($docIndex, fn($m) => ($m['status'] ?? '') === 'in_review'));

        $commentService = app(CommentService::class);
        $unresolvedComments = $commentService->allUnresolvedCount('admin');
        $commentSummary = $commentService->summary();

        // Count records
        $recordsDir = base_path('qms/records');
        $recordCount = 0;
        if (is_dir($recordsDir)) {
            $recordCount = count(glob($recordsDir . '/*.rec.json'));
        }

        return view('admin.guide', [
            'totalDocs' => $totalDocs,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'inReviewCount' => $inReviewCount,
            'unresolvedComments' => $unresolvedComments,
            'recordCount' => $recordCount,
        ]);
    }
}
