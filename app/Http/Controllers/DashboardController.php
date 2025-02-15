<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $projects = auth()->user()->projects;
        $tasks = auth()->user()->tasks;
        $users = \DB::table('project_user')
                ->whereIn('project_id', $projects->pluck('id'))
                ->where('user_id', '!=', auth()->user()->id)
                ->distinct()
                ->pluck('user_id');
        return view('dashboard', compact('projects', 'tasks', 'users'));
    }
}
