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
    Route::get('/qms/browse', [DocumentController::class, 'browse'])->name('documents.browse');
    Route::get('/qms/history', [DocumentController::class, 'history'])->name('documents.history');
    Route::get('/qms/revision/{hash}', [DocumentController::class, 'revision'])->name('documents.revision');
    Route::get('/qms/edit/{path}', [DocumentController::class, 'edit'])->where('path', '.*')->name('documents.edit');
    Route::put('/qms/save', [DocumentController::class, 'update'])->name('documents.update');
    Route::put('/qms/update-meta', [DocumentController::class, 'updateMeta'])->name('documents.update-meta');
    Route::get('/qms/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/qms/store', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/qms/move', [DocumentController::class, 'move'])->name('documents.move');
    Route::post('/qms/rename', [DocumentController::class, 'rename'])->name('documents.rename');
    Route::delete('/qms/delete', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/qms/directory', [DocumentController::class, 'createDirectory'])->name('documents.directory.store');
    Route::post('/qms/directory/rename', [DocumentController::class, 'renameDirectory'])->name('documents.directory.rename');
    Route::delete('/qms/directory', [DocumentController::class, 'destroyDirectory'])->name('documents.directory.destroy');
    Route::post('/qms/quick-create', [DocumentController::class, 'quickCreate'])->name('documents.quick-create');
    Route::post('/qms/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('/qms/download/{path}', [DocumentController::class, 'download'])->where('path', '.*')->name('documents.download');
    Route::get('/qms/changes', [DocumentController::class, 'changes'])->name('documents.changes');
    Route::post('/qms/publish', [DocumentController::class, 'publish'])->name('documents.publish');
    Route::post('/qms/discard', [DocumentController::class, 'discard'])->name('documents.discard');
    Route::post('/qms/discard-all', [DocumentController::class, 'discardAll'])->name('documents.discard-all');

    // Forms
    Route::get('/forms/create', [\App\Http\Controllers\FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [\App\Http\Controllers\FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/edit/{path}', [\App\Http\Controllers\FormController::class, 'edit'])->where('path', '.*')->name('forms.edit');
    Route::put('/forms/update', [\App\Http\Controllers\FormController::class, 'update'])->name('forms.update');
    Route::get('/forms/fill/{path}', [\App\Http\Controllers\FormController::class, 'fill'])->where('path', '.*')->name('forms.fill');
    Route::post('/forms/submit', [\App\Http\Controllers\FormController::class, 'submit'])->name('forms.submit');

    // Comments
    Route::post('/qms/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
    Route::post('/qms/comments/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->name('comments.reply');
    Route::post('/qms/comments/resolve', [\App\Http\Controllers\CommentController::class, 'resolve'])->name('comments.resolve');
    Route::post('/qms/comments/unresolve', [\App\Http\Controllers\CommentController::class, 'unresolve'])->name('comments.unresolve');
    Route::delete('/qms/comments', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::delete('/qms/comments/reply', [\App\Http\Controllers\CommentController::class, 'destroyReply'])->name('comments.destroy-reply');
    Route::get('/qms/comments/partial/{docId}', [\App\Http\Controllers\CommentController::class, 'partial'])->name('comments.partial');

    // Records
    Route::get('/records', [\App\Http\Controllers\RecordController::class, 'index'])->name('records.index');
    Route::get('/records/{filename}', [\App\Http\Controllers\RecordController::class, 'show'])->name('records.show');
    Route::delete('/records/{filename}', [\App\Http\Controllers\RecordController::class, 'destroy'])->name('records.destroy');

    // References
    Route::get('/references', [\App\Http\Controllers\ReferenceController::class, 'index'])->name('references.index');
    Route::get('/references/{path}', [\App\Http\Controllers\ReferenceController::class, 'show'])->where('path', '.*')->name('references.show');

    // QMS document viewer (catch-all, must be last)
    Route::get('/qms/{path?}', [DocumentController::class, 'index'])->where('path', '.*')->name('documents.index');

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
});

require __DIR__.'/auth.php';
