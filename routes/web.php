<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/approval-pending', function () {
    if (auth()->user()->approved) {
        return redirect('/dashboard');
    }
    return view('auth.approval-pending');
})->middleware('auth')->name('approval.pending');

Route::middleware('auth')->group(function () {
    // QMS document actions (must be before catch-all)
    Route::get('/browser', [DocumentController::class, 'browse'])->name('documents.browse');
    Route::get('/history', [DocumentController::class, 'history'])->name('documents.history');
    Route::get('/history/{hash}', [DocumentController::class, 'revision'])->name('documents.revision');
    Route::get('/documents/edit/{path}', [DocumentController::class, 'edit'])->where('path', '.*')->name('documents.edit');
    Route::put('/documents/save', [DocumentController::class, 'update'])->name('documents.update');
    Route::put('/documents/update-meta', [DocumentController::class, 'updateMeta'])->name('documents.update-meta');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents/store', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/documents/move', [DocumentController::class, 'move'])->name('documents.move');
    Route::post('/documents/rename', [DocumentController::class, 'rename'])->name('documents.rename');
    Route::delete('/documents/delete', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/directory', [DocumentController::class, 'createDirectory'])->name('documents.directory.store');
    Route::post('/documents/directory/rename', [DocumentController::class, 'renameDirectory'])->name('documents.directory.rename');
    Route::delete('/documents/directory', [DocumentController::class, 'destroyDirectory'])->name('documents.directory.destroy');
    Route::post('/documents/quick-create', [DocumentController::class, 'quickCreate'])->name('documents.quick-create');
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/documents/download/{path}', [DocumentController::class, 'download'])->where('path', '.*')->name('documents.download');
    Route::get('/documents/export/{path}', [\App\Http\Controllers\ExportController::class, 'pdf'])->where('path', '.*')->name('documents.export');
    Route::get('/documents/export-form/{path}', [\App\Http\Controllers\ExportController::class, 'formPdf'])->where('path', '.*')->name('documents.export-form');
    Route::get('/records/export/{filename}', [\App\Http\Controllers\ExportController::class, 'recordPdf'])->name('records.export');
    Route::get('/records/export-form/{formId}', [\App\Http\Controllers\ExportController::class, 'formRecordsZip'])->name('records.export-form');
    Route::get('/documents/export-xlsx/{path}', [\App\Http\Controllers\ExportController::class, 'xlsx'])->where('path', '.*')->name('documents.export-xlsx');
    Route::get('/documents/export-bulk/active', [\App\Http\Controllers\ExportController::class, 'activeBulkExport'])->name('documents.export-bulk-active');
    Route::post('/documents/export-bulk', [\App\Http\Controllers\ExportController::class, 'bulkExport'])->name('documents.export-bulk');
    Route::get('/documents/export-bulk/{export}/status', [\App\Http\Controllers\ExportController::class, 'bulkExportStatus'])->name('documents.export-bulk-status');
    Route::get('/documents/export-bulk/{export}/download', [\App\Http\Controllers\ExportController::class, 'bulkExportDownload'])->name('documents.export-bulk-download');
    Route::get('/documents/changes', [DocumentController::class, 'changes'])->name('documents.changes');
    Route::post('/documents/publish', [DocumentController::class, 'publish'])->name('documents.publish');
    Route::post('/documents/discard', [DocumentController::class, 'discard'])->name('documents.discard');
    Route::post('/documents/discard-all', [DocumentController::class, 'discardAll'])->name('documents.discard-all');

    // Forms
    Route::get('/forms/create', [\App\Http\Controllers\FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [\App\Http\Controllers\FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/edit/{path}', [\App\Http\Controllers\FormController::class, 'edit'])->where('path', '.*')->name('forms.edit');
    Route::put('/forms/update', [\App\Http\Controllers\FormController::class, 'update'])->name('forms.update');
    Route::get('/forms/fill/{path}', [\App\Http\Controllers\FormController::class, 'fill'])->where('path', '.*')->name('forms.fill');
    Route::post('/forms/submit', [\App\Http\Controllers\FormController::class, 'submit'])->name('forms.submit');

    // Comments
    Route::post('/documents/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::post('/documents/comments/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/documents/comments/resolve', [\App\Http\Controllers\CommentController::class, 'resolve'])->name('comments.resolve');
    Route::post('/documents/comments/unresolve', [\App\Http\Controllers\CommentController::class, 'unresolve'])->name('comments.unresolve');
    Route::delete('/documents/comments', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::delete('/documents/comments/reply', [\App\Http\Controllers\CommentController::class, 'destroyReply'])->name('comments.destroy-reply');
    Route::get('/documents/comments/partial/{docId}', [\App\Http\Controllers\CommentController::class, 'partial'])->name('comments.partial');

    // Records
    Route::get('/records', [\App\Http\Controllers\RecordController::class, 'index'])->name('records.index');
    Route::get('/records/form/{formId}', [\App\Http\Controllers\RecordController::class, 'formRecords'])->name('records.form');
    Route::get('/records/{filename}', [\App\Http\Controllers\RecordController::class, 'show'])->name('records.show');
    Route::delete('/records/{filename}', [\App\Http\Controllers\RecordController::class, 'destroy'])->name('records.destroy');

    // References
    Route::get('/references', [\App\Http\Controllers\ReferenceController::class, 'index'])->name('references.index');
    Route::get('/references/{path}', [\App\Http\Controllers\ReferenceController::class, 'show'])->where('path', '.*')->name('references.show');

    // QMS document viewer (catch-all, must be last)
    Route::get('/documents/{path?}', [DocumentController::class, 'index'])->where('path', '.*')->name('documents.index');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    // Admin Guide
    Route::get('/admin/guide', [\App\Http\Controllers\AdminGuideController::class, 'index'])->name('admin.guide');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User activity tracking
    Route::post('/api/activity', [\App\Http\Controllers\UserActivityController::class, 'track'])->name('activity.track');
    Route::get('/admin/activity', [\App\Http\Controllers\UserActivityController::class, 'index'])->name('activity.index');
    Route::get('/admin/activity/{user}', [\App\Http\Controllers\UserActivityController::class, 'show'])->name('activity.show');
    Route::get('/admin/activity/{user}/log', [\App\Http\Controllers\UserActivityController::class, 'log'])->name('activity.log');
    Route::get('/admin/activity/{user}/session/{sessionUid}', [\App\Http\Controllers\UserActivityController::class, 'session'])->name('activity.session');
    Route::delete('/admin/activity/{user}/clear', [\App\Http\Controllers\UserActivityController::class, 'clear'])->name('activity.clear');
});

require __DIR__.'/auth.php';
