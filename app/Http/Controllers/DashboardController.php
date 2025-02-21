<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
class DashboardController extends Controller
{
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

        return view('dashboard', compact('projects', 'tasks', 'users', 'projects_invite'));
    }
}
