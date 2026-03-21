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
    return view('auth.approval-pending');
})->middleware('auth')->name('approval.pending');

Route::middleware('auth')->group(function () {
    // QMS document actions (must be before catch-all)
    Route::get('/qms/browse', [DocumentController::class, 'browse'])->name('documents.browse');
    Route::get('/qms/history', [DocumentController::class, 'history'])->name('documents.history');
    Route::get('/qms/revision/{hash}', [DocumentController::class, 'revision'])->name('documents.revision');
    Route::get('/qms/edit/{path}', [DocumentController::class, 'edit'])->where('path', '.*')->name('documents.edit');
    Route::put('/qms/save', [DocumentController::class, 'update'])->name('documents.update');
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
    Route::get('/forms/fill/{path}', [\App\Http\Controllers\FormController::class, 'fill'])->where('path', '.*')->name('forms.fill');
    Route::post('/forms/submit', [\App\Http\Controllers\FormController::class, 'submit'])->name('forms.submit');
    Route::get('/forms/submission/{submission}', [\App\Http\Controllers\FormController::class, 'submission'])->name('forms.submission');
    Route::get('/forms/submissions/{formId}', [\App\Http\Controllers\FormController::class, 'submissions'])->name('forms.submissions');

    // QMS document viewer (catch-all, must be last)
    Route::get('/qms/{path?}', [DocumentController::class, 'index'])->where('path', '.*')->name('documents.index');

    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
