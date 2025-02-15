<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    // Afficher tous les projets de l'utilisateur connecté
    public function index()
    {
        $projects = Auth::user()->projects; // Les projets de l'utilisateur connecté
        return view('projects.index', compact('projects'));
    }

    // Afficher le formulaire de création d'un projet
    public function create()
    {
        return view('projects.create');
    }

    // Créer un nouveau projet
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'en_cours',
        ]);

        // Ajouter l'utilisateur comme membre du projet
        $project->users()->attach(Auth::id(), ['role' => 'admin']); 

        return redirect()->route('projects.index')->with('success', 'Projet créé avec succès!');
    }

    // Afficher un projet spécifique
    public function show(Project $project)
    {
        $this->authorize('view', $project); // Vérification que l'utilisateur a accès au projet
        return view('projects.show', compact('project'));
    }
    
    // Ajouter un utilisateur au projet
    public function addUser(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,membre',
        ]);

        $user = User::findOrFail($request->user_id);
        $project->users()->attach($user, ['role' => $request->role]);

        return redirect()->route('projects.show', $project)->with('success', 'Utilisateur ajouté avec succès!');
    }
}
