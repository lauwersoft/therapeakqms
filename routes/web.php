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

Route::middleware('auth')->group(function () {
    // QMS document actions (must be before catch-all)
    Route::get('/qms/history', [DocumentController::class, 'history'])->name('documents.history');
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
    Route::get('/qms/changes', [DocumentController::class, 'changes'])->name('documents.changes');
    Route::post('/qms/publish', [DocumentController::class, 'publish'])->name('documents.publish');
    Route::post('/qms/discard', [DocumentController::class, 'discard'])->name('documents.discard');
    Route::post('/qms/discard-all', [DocumentController::class, 'discardAll'])->name('documents.discard-all');

    // QMS document viewer (catch-all, must be last)
    Route::get('/qms/{path?}', [DocumentController::class, 'index'])->where('path', '.*')->name('documents.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
