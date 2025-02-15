<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Afficher toutes les tâches d'un projet
    public function index(Project $project)
    {
        $tasks = $project->tasks;
        return view('tasks.index', compact('tasks', 'project'));
    }

    // Afficher le formulaire pour créer une tâche
    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

    // Créer une nouvelle tâche
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $task = $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => 'en cours',
        ]);

        return redirect()->route('tasks.index', $project)->with('success', 'Tâche créée avec succès!');
    }

    // Modifier le statut d'une tâche
    public function updateStatus(Task $task, Request $request)
    {
        $request->validate([
            'status' => 'required|in:en cours,terminée,suspendue',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Statut de la tâche mis à jour!');
    }

    // Assigner une tâche à un utilisateur
    public function assignUser(Task $task, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $task->users()->attach($user);

        return back()->with('success', 'Utilisateur assigné à la tâche!');
    }
}
