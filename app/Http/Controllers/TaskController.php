<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectNotification;

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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:en cours,suspendue,terminée',
        ]);
        
        $task = $project->tasks()->create($validated);
        $assigned_users = $request->input('users', []);
        foreach($assigned_users as $user_id){
            if($project->users->contains($user_id)){
                $user = User::findOrFail($user_id);
                $task->users()->attach($user);
                $notif = Notification::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'type' => 'info',
                    'message' => 'Vous avez été assigné à la tâche ' . $task->title . ' du projet ' . $project->title,
                    'is_read' => false,
                ]);
                Mail::to($user->email)->send(new ProjectNotification($project, $notif->message));
            }
        }
        return back()->with('success', 'Tâche créée avec succès!');
    }

    public function update(Project $project, Task $task, Request $request){
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:en cours,suspendue,terminée',
        ]);
        $task->update($validated);
        $assigned_users = $request->input('users', []);
        $task->users()->detach();
        foreach($assigned_users as $user_id){
            if($project->users->contains($user_id)){
                $user = User::findOrFail($user_id);
                $task->users()->attach($user);
                $notif = Notification::create([
                    'user_id' => $user->id,
                    'project_id' => $project->id,
                    'type' => 'info',
                    'message' => 'Vous avez été assigné à la tâche ' . $task->title . ' du projet ' . $project->title,
                    'is_read' => false,
                ]);
                Mail::to($user->email)->send(new ProjectNotification($project, $notif->message));
            }
        }
        return back()->with('success', 'Tâche mise à jour avec succès!');
    }
        
    
    public function destroy(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) {
            abort(403, "Cette tâche n'appartient pas à ce projet.");
        }

        $task->delete();
        return back()->with('success', 'Tâche supprimée avec succès!');
    }
}
