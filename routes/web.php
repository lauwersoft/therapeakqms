<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents', [DocumentController::class, 'update'])->name('documents.update');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/documents/move', [DocumentController::class, 'move'])->name('documents.move');
    Route::post('/documents/rename', [DocumentController::class, 'rename'])->name('documents.rename');
    Route::delete('/documents', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::post('/documents/directory', [DocumentController::class, 'createDirectory'])->name('documents.directory.store');
    Route::post('/documents/directory/rename', [DocumentController::class, 'renameDirectory'])->name('documents.directory.rename');
    Route::delete('/documents/directory', [DocumentController::class, 'destroyDirectory'])->name('documents.directory.destroy');
    Route::post('/documents/quick-create', [DocumentController::class, 'quickCreate'])->name('documents.quick-create');
    Route::get('/documents/changes', [DocumentController::class, 'changes'])->name('documents.changes');
    Route::post('/documents/publish', [DocumentController::class, 'publish'])->name('documents.publish');
    Route::post('/documents/discard', [DocumentController::class, 'discard'])->name('documents.discard');
    Route::post('/documents/discard-all', [DocumentController::class, 'discardAll'])->name('documents.discard-all');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
