<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Profil de l'utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour les projets
    Route::resource('projects', ProjectController::class);
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/teams', [TeamController::class, 'index'])->name('teams');

    // Ajouter un utilisateur à un projet
    Route::post('projects/{project}/add-user', [ProjectController::class, 'addUser'])->name('projects.addUser');

    // Routes pour les tâches
    Route::resource('projects.tasks', TaskController::class);
    Route::resource('projects.documents', DocumentController::class);
    Route::put('/projects/{project}/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
});

require __DIR__.'/auth.php';
