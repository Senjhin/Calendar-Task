<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    if(auth()->check()) {
        return redirect()->route('tasks.index');
    } else {
        return redirect()->route('login');
    }
});
Route::get('/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
Route::get('tasks/shared-tasks/{token}', [TaskController::class, 'showShared'])->name('tasks.show_shared');


Route::middleware(['auth'])->group(function () {

    Route::get('tasks/history', [TaskController::class, 'allRevisions'])->name('tasks.history');
    Route::get('tasks/{task}/history', [TaskController::class, 'taskRevisions'])->name('tasks.history.task');

    Route::resource('tasks', TaskController::class);


    Route::get('tasks/{task}/share', [TaskController::class, 'share'])->name('tasks.share');

    Route::get('/google/connect', [GoogleAuthController::class, 'redirect'])->name('google.connect');
    Route::post('/tasks/{task}/sync_google', [GoogleAuthController::class, 'sync'])->name('tasks.sync_google');
    Route::post('/google/logout', [GoogleAuthController::class, 'logout'])->name('google.logout');
});
require __DIR__.'/auth.php';
