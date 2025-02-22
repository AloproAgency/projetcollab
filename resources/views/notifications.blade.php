<?php 
$page = 'dashboard';
?>
@extends('layouts.app')

@section('content')
 <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-6 mb-6">
            <div class="bg-white rounded-xl shadow-md overflow-scroll">
                <!-- En-tête avec dégradé -->
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-6">
                    <div class="flex items-center">
                        <i class="fas fa-bell text-2xl text-white mr-5"></i>
                        <h2 class="text-xl my-1 font-bold text-white">Notifications</h2>
                    </div>
                </div>
        
                <!-- Liste des projets -->
                <div class="divide-y divide-teal-100">
                    @if ($notifications->count())
                        @foreach($notifications as $notification)
                        <div onclick="window.location.href='{{ route('projects.show', $notification->project_id) }}'" class="group hover:bg-teal-50 transition-colors cursor-pointer">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Indicateur de statut avec tooltip -->
                                        <div class="relative">
                                            <div class="w-3 h-3 rounded-full @if($notification->type == 'warning') bg-yellow-400 @else bg-green-400 @endif">
                                            </div>
                                        </div>
                                        
                                        <!-- Titre et description -->
                                        <div>
                                            <h3 class="text-md font-medium text-gray-900">{{ $notification->message }}</h3>                                                                    
                                        </div>
                                    </div>
            
                                    <!-- Informations supplémentaires -->
                                    <div class="flex items-center space-x-6">
                                        <!-- Date de fin -->
                                        <div class="text-sm text-gray-500">
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-bell text-4xl"></i>
                                <p class="text-lg font-medium mt-4">Aucune notification</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div style="margin-bottom: 100px"></div>
            </div>
        </div>
    </div>
@endsection