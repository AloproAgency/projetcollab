<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
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

    // Ajouter un utilisateur à un projet
    Route::post('projects/{project}/add-user', [ProjectController::class, 'addUser'])->name('projects.addUser');

    // Routes pour les tâches
    Route::resource('tasks', TaskController::class);

    // Mettre à jour le statut d'une tâche
    Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');

    // Assigner un utilisateur à une tâche
    Route::post('tasks/{task}/assign-user', [TaskController::class, 'assignUser'])->name('tasks.assignUser');
});

require __DIR__.'/auth.php';
