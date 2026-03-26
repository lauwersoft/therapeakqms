<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use App\Services\DocumentMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\MarkdownConverter;

class AdminGuideController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()->isAdmin() && ! $request->user()->isEditor()) {
            abort(403);
        }

        // QMS health stats
        $basePath = base_path('qms/documents');
        $docIndex = DocumentMetadata::index($basePath);

        $totalDocs = count($docIndex);
        $draftCount = count(array_filter($docIndex, fn($m) => ($m['status'] ?? '') === 'draft'));
        $approvedCount = count(array_filter($docIndex, fn($m) => ($m['status'] ?? '') === 'approved'));
        $inReviewCount = count(array_filter($docIndex, fn($m) => ($m['status'] ?? '') === 'in_review'));

        $commentService = app(CommentService::class);
        $unresolvedComments = $commentService->allUnresolvedCount('admin');

        $recordsDir = base_path('qms/records');
        $recordCount = is_dir($recordsDir) ? count(glob($recordsDir . '/*.rec.json')) : 0;

        // Render guide markdown
        $guidePath = base_path('qms/ADMIN_GUIDE.md');
        $guideHtml = '';
        if (File::exists($guidePath)) {
            $raw = File::get($guidePath);

            $environment = new Environment([
                'html_input' => 'strip',
                'allow_unsafe_links' => false,
            ]);
            $environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
            $environment->addExtension(new TableExtension());
            $environment->addExtension(new TaskListExtension());

            $converter = new MarkdownConverter($environment);
            $guideHtml = $converter->convert($raw)->getContent();
        }

        return view('admin.guide', [
            'totalDocs' => $totalDocs,
            'draftCount' => $draftCount,
            'approvedCount' => $approvedCount,
            'inReviewCount' => $inReviewCount,
            'unresolvedComments' => $unresolvedComments,
            'recordCount' => $recordCount,
            'guideHtml' => $guideHtml,
        ]);
    }
}
