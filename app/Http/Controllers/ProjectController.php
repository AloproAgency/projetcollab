<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    // Afficher tous les projets de l'utilisateur connecté
    public function index()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)->orderBy('updated_at', 'desc')->withCount([
            'tasks', 
            'tasks as remaining_tasks_count' => function ($query) {
                $query->where('status', 'terminée'); 
            }
        ])->get();

        $projects_invite = Project::whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->where('user_id', '!=', $user->id)  // Exclure les projets où l'utilisateur est le propriétaire
        ->withCount(['tasks', 
                     'tasks as remaining_tasks_count' => function ($query) {
                         $query->where('status', '!=', 'terminée'); // Tâches restantes (pas terminées)
                     }])
        ->orderBy('updated_at', 'desc')
        ->get();

        $tasks = $user->tasks;
        $users = \DB::table('project_user')
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('user_id', '!=', $user->id)
            ->distinct()
            ->pluck('user_id');
        return view('projects', compact('projects', 'tasks', 'users', 'projects_invite'));
    }

    // Afficher le formulaire de création d'un projet
    public function create()
    {
        return view('projects.create');
    }

    // Créer un nouveau projet
    public function store(Request $request)
    {
        if($request->start_date > $request->end_date){
            return back()->with('error', 'La date de début doit être inférieure à la date de fin!');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:en_attente,en_cours,termine',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'user_id' => Auth::id(),
        ]);

        // Ajouter l'utilisateur comme membre du projet
        $project->users()->attach(Auth::id(), ['role' => 'admin', 'fonction' => 'Créateur']); 

        return back()->with('success', 'Projet créé avec succès!');
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        if($request->onlyState){
            $request->validate([
                'status' => 'required|in:en_attente,en_cours,termine',
            ]);
            $project->update([
                'status' => $request->status,
            ]);
            return back()->with('success', 'Statut du projet mis à jour avec succès!');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_attente,en_cours,termine',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $project->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return back()->with('success', 'Projet mis à jour avec succès!');
    }

    // Afficher un projet spécifique
    public function show(Project $project)
    {
        $this->authorize('view', $project); 
        $tasks = $project->tasks()->orderBy('created_at', 'desc')->get();
        $documents = $project->documents()->orderBy('created_at', 'desc')->get();
        $members = $project->users()->withPivot('role', 'fonction')->get();
        return view('projectview', compact('project', 'tasks', 'members', 'documents'));
    }
    
    // Ajouter un utilisateur au projet
    public function addUser(Request $request, Project $project)
    {
        $request->validate([
            'fonction' => 'required|string|max:255',
            'email' => 'required|exists:users,email',
            'role' => 'required|in:admin,membre',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if($project->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->doesntExist()){
            return back()->with('error', 'Vous n\'êtes pas autorisé à ajouter un utilisateur au projet!');
        }
        if($user->id == Auth::id()){
            return back()->with('error', 'Vous êtes déjà membre du projet!');
        }
        if($project->users()->where('user_id', $user->id)->exists()){
            return back()->with('error', 'Cet utilisateur est déjà membre du projet!');
        }
        $project->users()->syncWithoutDetaching([$user->id => ['role' => $request->role, 'fonction' => $request->fonction]]);

        return back()->with('success', 'Utilisateur ajouté avec succès!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project); // Vérifie que l'utilisateur est admin du projet

        $project->delete();

        return back()->with('success', 'Projet supprimé avec succès!');
    }
}
