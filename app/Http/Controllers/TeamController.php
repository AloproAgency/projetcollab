<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
class TeamController extends Controller
{
    function index() {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)->pluck('id');
    
        if ($projects->isEmpty()) {
            return view('teams', ['members' => collect()]);
        }
    
        $members = User::whereHas('projects', function ($query) use ($projects) {
            $query->whereIn('projects.id', $projects);
        })
        ->where('id', '!=', $user->id)
        ->distinct()
        ->get();
    
        $members->each(function ($member) {
            $member->projects_count = Project::whereHas('users', function ($query) use ($member) {
                $query->where('user_id', $member->id)
                    ->where('projects.user_id', '!=', $member->id);
            })->count();
        });
    
        return view('teams', compact('members'));
    }
    
}
