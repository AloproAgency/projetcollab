<?php
$page = 'teams';
?>
@extends('layouts.app')

@section('content')

<h3 class="text-2xl font-semibold text-gray-800 my-6 flex items-center gap-2">
    <span class="pl-2">Personnes travaillant sur vos propres projets</span>
</h3>
<!-- Team Members Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($members as $user)
    <div class=" relative bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-sm transition-all duration-300 border border-gray-100">        
        <div class="relative p-6">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="w-16 h-16 bg-black flex items-center justify-center rounded-full text-xl font-bold text-gray-200">
                        {{strtoupper(substr($user->name, 0, 2))}}
                    </div>
                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-400 border-2 border-white rounded-full"></div>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900 group-hover:text-white transition-colors">{{ $user->name }}</h3>
                    <p class="text-gray-600 group-hover:text-white/90 transition-colors">{{ $user->email }}</p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100 group-hover:border-white/20 transition-colors">
                <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                        <span class="text-sm text-gray-500 group-hover:text-white/80 transition-colors flex items-center space-x-1">
                            <i class="fas fa-project-diagram"></i>
                            <span>participe à {{ $user->projects_count }} de vos projets</span>
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="mailto:{{ $user->email }}">
                            <button class="p-2 text-gray-500 hover:text-violet-600 group-hover:text-white transition-colors">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection