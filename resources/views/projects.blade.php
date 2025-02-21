<?php 
$page = 'projects';
?>
@extends('layouts.app')

@section('content')
<div x-data="{ 
    open: false,
    isEditing: false,
    projectId: null,
    init() {
        this.open = false;
    },
    initForm(project = null) {
        this.isEditing = !!project;
        this.open = true;
        if (project) {
            this.projectId = project.id;
            setTimeout(() => {
                document.getElementById('project-form').action = `/projects/${project.id}`;
                document.getElementById('title').value = project.title;
                document.getElementById('description').value = project.description;
                document.getElementById('start_date').value = project.start_date;
                document.getElementById('end_date').value = project.end_date;
                document.getElementById('status').value = project.status;
                document.getElementById('method').value = 'PUT';
            });
        } else {
            this.projectId = null;
            setTimeout(() => {
                document.getElementById('project-form').action = '/projects';
                document.getElementById('project-form').reset();
                document.getElementById('method').value = 'POST';
            });
        }
    }
}">

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Projects List -->
        <div class="lg:col-span-6 mb-6">
            <div class="bg-white rounded-xl shadow-md overflow-scroll">
                <!-- En-tête avec dégradé -->
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white">Vos propres projets</h2>
                        <button 
                            @click="initForm()" 
                            class="inline-flex items-center px-4 py-2 bg-white text-teal-600 rounded-lg font-medium shadow-sm hover:bg-teal-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouveau projet
                        </button>
                    </div>
                </div>
        
                <!-- Liste des projets -->
                <div class="divide-y divide-teal-100">
                    @if ($projects->count())
                        @foreach($projects as $project)
                        <div onclick="window.location.href='{{ route('projects.show', $project) }}'" class="group hover:bg-teal-50 transition-colors cursor-pointer">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Indicateur de statut avec tooltip -->
                                        <div class="relative">
                                            <div class="w-3 h-3 rounded-full 
                                                @if($project->status == 'en_cours') bg-yellow-400
                                                @elseif($project->status == 'termine') bg-emerald-400
                                                @else bg-red-400 @endif
                                                ring-4 ring-opacity-30
                                                @if($project->status == 'en_cours') ring-yellow-100
                                                @elseif($project->status == 'termine') ring-emerald-100
                                                @else ring-red-100 @endif"
                                                x-tooltip="'Statut: {{ $project->status }}'">
                                            </div>
                                        </div>
                                        
                                        <!-- Titre et description -->
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $project->title }}</h3>                                        
                                            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                                                @php
                                                    $completedTasks = $project->tasks_count - $project->remaining_tasks_count;
                                                    $percentage = $project->tasks_count > 0 ? ($completedTasks / $project->tasks_count) * 100 : 0;
                                                @endphp
                                                <div 
                                                    class="bg-teal-500 h-4 rounded-full transition-all duration-500" 
                                                    style="width: {{ round($percentage) }}%;"
                                                ></div>
                                            </div>
                                    
                                            <!-- Légende -->
                                            <p class="text-sm text-gray-600">
                                                {{ $completedTasks }} / {{ $project->tasks_count }} tâches terminées
                                            </p>
                                        </div>
                                    </div>
            
                                    <!-- Informations supplémentaires -->
                                    <div class="flex items-center space-x-6">
                                        <!-- Date de fin -->
                                        <div class="text-sm text-gray-500">
                                            @if($project->status == 'en_cours') 
                                                <span >Fin prévu pour:</span>
                                                {{ \Carbon\Carbon::parse($project->end_date)->isoFormat('D MMM') }}
                                            @elseif($project->status == 'termine')
                                                Terminé
                                            @else
                                                En attente
                                            @endif
                                            
                                        </div>
            
                                        <!-- Menu d'actions -->
                                        <div class="relative opacity-0 group-hover:opacity-100 transition-opacity" x-data="{ open: false }">
                                            <button @click="event.stopPropagation(); open = !open;" class="p-2 rounded-lg hover:bg-teal-100">
                                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                            </button>
            
                                            <!-- Menu déroulant -->
                                            <div x-show="open" 
                                                @click.away="open = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <!-- Voir -->
                                                    <a href="{{ route('projects.show', $project) }}" 
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Voir
                                                    </a>
                                                    
                                                    <!-- Modifier -->
                                                    <button @click="event.stopPropagation(); $dispatch('open-edit-modal', @js($project)); open = false;"
                                                            class="group w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Modifier
                                                    </button>
                                                    
                                                    <!-- Supprimer -->
                                                    <button @click="event.stopPropagation(); $refs.deleteForm{{ $project->id }}.submit()" 
                                                            class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                    <form onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet projet ?');" x-ref="deleteForm{{ $project->id }}" 
                                                        action="{{ route('projects.destroy', $project) }}" 
                                                        method="POST" 
                                                        class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl"></i>
                                <p class="text-lg font-medium mt-4">Aucun projet n'a été créer</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div style="margin-bottom: 100px"></div>
            </div>
        </div>
        <div class="lg:col-span-6 mb-6">
            <div class="bg-white rounded-xl shadow-md overflow-scroll">
                <!-- En-tête avec dégradé -->
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl my-1 font-bold text-white">Projets auxquels vous etes invités</h2>
                    </div>
                </div>
        
                <!-- Liste des projets -->
                <div class="divide-y divide-teal-100">
                    @if ($projects_invite->count())
                        @foreach($projects_invite as $project)
                        <div onclick="window.location.href='{{ route('projects.show', $project) }}'" class="group hover:bg-teal-50 transition-colors cursor-pointer">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Indicateur de statut avec tooltip -->
                                        <div class="relative">
                                            <div class="w-3 h-3 rounded-full 
                                                @if($project->status == 'en_cours') bg-yellow-400
                                                @elseif($project->status == 'termine') bg-emerald-400
                                                @else bg-red-400 @endif
                                                ring-4 ring-opacity-30
                                                @if($project->status == 'en_cours') ring-yellow-100
                                                @elseif($project->status == 'termine') ring-emerald-100
                                                @else ring-red-100 @endif"
                                                x-tooltip="'Statut: {{ $project->status }}'">
                                            </div>
                                        </div>
                                        
                                        <!-- Titre et description -->
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">{{ $project->title }}</h3>                                        
                                            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                                                @php
                                                    $completedTasks = $project->tasks_count - $project->remaining_tasks_count;
                                                    $percentage = $project->tasks_count > 0 ? ($completedTasks / $project->tasks_count) * 100 : 0;
                                                @endphp
                                                <div 
                                                    class="bg-teal-500 h-4 rounded-full transition-all duration-500" 
                                                    style="width: {{ round($percentage) }}%;"
                                                ></div>
                                            </div>
                                    
                                            <!-- Légende -->
                                            <p class="text-sm text-gray-600">
                                                {{ $completedTasks }} / {{ $project->tasks_count }} tâches terminées
                                            </p>
                                        </div>
                                    </div>
            
                                    <!-- Informations supplémentaires -->
                                    <div class="flex items-center space-x-6">
                                        <!-- Date de fin -->
                                        <div class="text-sm text-gray-500">
                                            @if($project->status == 'en_cours') 
                                                <span >Fin prévu pour:</span>
                                                {{ \Carbon\Carbon::parse($project->end_date)->isoFormat('D MMM') }}
                                            @elseif($project->status == 'termine')
                                                Terminé
                                            @else
                                                En attente
                                            @endif
                                            
                                        </div>
            
                                        <!-- Menu d'actions -->
                                        <div class="relative opacity-0 group-hover:opacity-100 transition-opacity" x-data="{ open: false }">
                                            <button @click="event.stopPropagation(); open = !open;" class="p-2 rounded-lg hover:bg-teal-100">
                                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                </svg>
                                            </button>
            
                                            <!-- Menu déroulant -->
                                            <div x-show="open" 
                                                @click.away="open = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                                <div class="py-1">
                                                    <!-- Voir -->
                                                    <a href="{{ route('projects.show', $project) }}" 
                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Voir
                                                    </a>
                                                    
                                                    <!-- Modifier -->
                                                    @if ($project->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists())
                                                    <button @click="event.stopPropagation(); $dispatch('open-edit-modal', @js($project)); open = false;"
                                                            class="group w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Modifier
                                                    </button>
                                                    @endif
                                                    
                                                    <!-- Supprimer -->
                                                    @if ($project->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists())
                                                    <button @click="event.stopPropagation(); $refs.deleteForm{{ $project->id }}.submit()" 
                                                            class="group flex w-full items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Supprimer
                                                    </button>
                                                    <form onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet projet ?');" x-ref="deleteForm{{ $project->id }}" 
                                                        action="{{ route('projects.destroy', $project) }}" 
                                                        method="POST" 
                                                        class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div>
                            <div class="p-6 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl"></i>
                                <p class="text-lg font-medium mt-4">Aucun projet</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div style="margin-bottom: 100px"></div>
            </div>
        </div>
    </div>

   <!-- Modal -->
<div x-show="open"
x-cloak
@keydown.escape.window="open = false"
@open-edit-modal.window="initForm($event.detail)"
class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
aria-modal="true"
role="dialog"
aria-labelledby="modal-title">
<div class="relative min-h-screen flex items-center justify-center p-4">
   <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6 md:p-8"
        @click.away="open = false">
       <div class="flex justify-between items-center mb-4">
           <h2 class="text-xl font-semibold text-gray-800" id="modal-title" x-text="isEditing ? 'Modifier le projet' : 'Ajouter un projet'">
               Ajouter un projet
           </h2>
           <button @click="open = false" class="text-gray-500 hover:text-gray-800">
               <i class="fas fa-times"></i>
           </button>
       </div>

       <form id="project-form" method="POST" action="{{ route('projects.store') }}" class="space-y-4">
           @csrf
           <input type="hidden" id="method" name="_method" value="POST">

           <div>
               <label for="title" class="block text-sm font-medium text-gray-700">Nom du projet</label>
               <div class="mt-1">
                   <input type="text"
                          id="title"
                          name="title"
                          required
                          class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                          placeholder="Nom du projet">
               </div>
           </div>

           <div>
               <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
               <div class="mt-1">
                   <textarea id="description"
                             name="description"
                             rows="3"
                             required
                             class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                             placeholder="Description du projet"></textarea>
               </div>
           </div>

           <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div>
                   <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
                   <div class="mt-1">
                       <input type="date"
                              id="start_date"
                              name="start_date"
                              required
                              class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                   </div>
               </div>

               <div>
                   <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin</label>
                   <div class="mt-1">
                       <input type="date"
                              id="end_date"
                              name="end_date"
                              required
                              class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                   </div>
               </div>
           </div>

           <div>
               <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
               <div class="mt-1">
                   <select id="status" name="status" required class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                       <option value="en_cours">En cours</option>
                       <option value="en_attente">En attente</option>
                       <option value="termine">Terminé</option>
                   </select>
               </div>
           </div>

           <div class="flex justify-end space-x-2">
               <button type="button" @click="open = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl">
                   Annuler
               </button>
               <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-xl">
                   Enregistrer
               </button>
           </div>
       </form>
   </div>
</div>
</div>

</div>
@endsection