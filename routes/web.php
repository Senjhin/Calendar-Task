<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');  // Widok po zalogowaniu - stwórz plik dashboard.blade.php
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // 👇 Najpierw konkretna trasa
    Route::get('tasks/revisions', [TaskController::class, 'allRevisions'])->name('tasks.revisions');
    Route::resource('tasks', TaskController::class);

    // Widok generowania linku do udostępnienia
    Route::get('tasks/{task}/share', [TaskController::class, 'share'])->name('tasks.share');


// 👇 Potem trasy dynamiczne
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

    // Historia konkretnego zadania (dodaj tę trasę)
    Route::get('tasks/{task}/revisions', [TaskController::class, 'taskRevisions'])->name('tasks.revisions.task');

    // Synchronizacja z Google Calendar (POST)
    Route::post('tasks/{task}/sync-google', [TaskController::class, 'syncGoogleCalendar'])->name('tasks.sync_google');


});

Route::get('tasks/shared-tasks/{token}', [TaskController::class, 'showShared'])->name('tasks.show_shared');
require __DIR__.'/auth.php';
