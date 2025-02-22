<?php 
$page = 'projects';
?>
@extends('layouts.app')

@section('content')
<div x-data="{ 
    opentache: false,
    isEditing: false,
    taskId: null,
    initForm(task = null) {
        this.isEditing = !!task;
        this.opentache = true;
        if (task) {
            this.taskId = task.id;
            setTimeout(() => {
                document.getElementById('task-form').action = `/projects/{{$project->id}}/tasks/${task.id}`;
                document.getElementById('task-title').value = task.title;
                document.getElementById('task-description').value = task.description;
                document.getElementById('task-due_date').value = task.due_date;
                document.getElementById('task-status').value = task.status;
                document.getElementById('task-method').value = 'PUT';
                const usersSelect = document.getElementById('users');
                const assignedUsers = Array.isArray(task.members) ? task.members : [];
                Array.from(usersSelect.options).forEach(option => {
                    option.selected = assignedUsers.includes(parseInt(option.value));
                });
            });
        } else {
            this.taskId = null;
            setTimeout(() => {
                document.getElementById('task-form').action = `/projects/{{$project->id}}/tasks`;
                document.getElementById('task-form').reset();
                document.getElementById('task-method').value = 'POST';
            });
        }
    }
}">

<div x-data="{openmember: false}">
<div x-data="{openfilemodal: false}">

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto bg-white rounded-2xl overflow-hidden" x-data="{ activeTab: localStorage.getItem('tab')==null?'taches':localStorage.getItem('tab'), projectStatus: 'En cours' }">
        <!-- Header with glassmorphism effect -->
        <div class="relative bg-gradient-to-r from-teal-600 to-teal-800 p-8">
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-3xl font-bold text-white flex items-center space-x-4">
                        <i class="fas fa-rocket text-3xl"></i>
                        <span>{{$project->title}}</span>
                    </h2>
                    <!-- Project Status (Editable Flag) -->
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <div>
                            <button {{$project->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists()?'':'disabled'}} type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-teal-500" id="menu-button" aria-expanded="false" aria-haspopup="true" @click="open = !open">
                                <span class="{{ 
                                    $project->status === 'en_cours' ? 'text-amber-500' : 
                                    ($project->status === 'termine' ? 'text-green-500' : 
                                    ($project->status === 'en_attente' ? 'text-red-500' : '')) }}">
                                    <i class="fas fa-flag mr-2"></i>
                                </span>
                                <span>{{ 
                                    $project->status === 'en_cours' ? 'En cours' : 
                                    ($project->status === 'termine' ? 'Terminé' : 
                                    ($project->status === 'en_attente' ? 'En attente' : '')) }}</span>
                                <svg {{$project->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists()?'':'hidden'}} class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-40" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" @click.away="open = false">
                            <div class="py-1" role="none">
                                <button @click="$refs.setLiveForm.submit()" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 hover:text-teal-500" role="menuitem" tabindex="-1" id="menu-item-0">
                                    <i class="fas fa-flag text-amber-500 mr-2"></i> En cours
                                </button>
                                <form x-ref="setLiveForm" 
                                    action="{{ route('projects.update', $project->id) }}" 
                                    class="hidden" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="status" value="en_cours">
                                    <input type="text" name="onlyState" value="yes">
                                </form>
                                <button @click="$refs.setFinishForm.submit()" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 hover:text-teal-500" role="menuitem" tabindex="-1" id="menu-item-1" @click.prevent="projectStatus = 'Terminé'; open = false;">
                                    <i class="fas fa-flag text-green-500 mr-2"></i> Terminé
                                </button>
                                <form x-ref="setFinishForm" 
                                    action="{{ route('projects.update', $project->id) }}" 
                                    class="hidden" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="status" value="termine">
                                    <input type="text" name="onlyState" value="yes">
                                </form>
                                <button @click="$refs.setWaitForm.submit()" class="w-full text-left text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100 hover:text-teal-500" role="menuitem" tabindex="-1" id="menu-item-2" @click.prevent="projectStatus = 'En attente'; open = false;">
                                    <i class="fas fa-flag text-red-500 mr-2"></i> En attente
                                </button>
                                <form x-ref="setWaitForm" 
                                    action="{{ route('projects.update', $project->id) }}" 
                                    class="hidden" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="status" value="en_attente">
                                    <input type="text" name="onlyState" value="yes">
                                </form>
                            </div>
                        </div>
                        <div class="w-10 h-10 text-black bg-white rounded-lg p-2" x-show="open" x-cloak>
                            <button>
                                <i class="fas fa-edit mr-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-6 text-white/80 mb-4">
                    <span>{{$project->description}}</span>
                </div>
                <!-- Project Dates -->
                <div class="flex items-center space-x-6 text-white/80 mb-4">
                    <div>
                        <i class="fas fa-calendar-day mr-2"></i>
                        <span>Début: {{$project->start_date}}</span>
                    </div>
                    <div>
                        <i class="fas fa-calendar-check mr-2"></i>
                        <span>Fin: {{$project->end_date}}</span>
                    </div>
                </div>

                <!-- Mini stats cards -->
                @php
                    $totalTasks = $tasks->count();
                    $endTasks = $tasks->filter(function($task) {
                        return $task->status == 'terminée';
                    })->count();
                    $percentage = $totalTasks > 0 ? round(($endTasks / $totalTasks) * 100, 2) : 0;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 flex items-center space-x-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-tasks text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/70 text-sm">Tâches terminées</p>
                            <p class="text-white font-bold text-xl">{{$endTasks}}/{{$totalTasks}}</p>
                        </div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 flex items-center space-x-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/70 text-sm">Membres</p>
                            <p class="text-white font-bold text-xl">{{$members->count()}}</p>
                        </div>
                    </div>
                    @php
                        use Carbon\Carbon;

                        $now = Carbon::now()->startOfDay();
                        $endDate = Carbon::parse($project->end_date)->startOfDay();
                        $daysLeft = $now->diffInDays($endDate, true);
                    @endphp
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 flex items-center space-x-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/70 text-sm">Jours restant</p>
                            <p class="text-white font-bold text-xl">{{$daysLeft}}</p>
                        </div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 flex items-center space-x-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-white/70 text-sm">Progression</p>
                            <p class="text-white font-bold text-xl">{{$percentage}}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Navigation -->
        <div class="bg-white border-b border-gray-100 overflow-x-auto">
            <nav class="flex justify-start md:justify-center space-x-4 md:space-x-8 px-4 md:px-6" aria-label="Tabs">
                <button @click="activeTab = 'taches'; localStorage.setItem('tab', 'taches')"
                        :class="{ 'text-teal-600 border-teal-600': activeTab === 'taches', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'taches' }"
                        class="flex items-center space-x-2 py-4 md:py-6 px-2 md:px-4 border-b-2 font-medium transition-all duration-300 group whitespace-nowrap">
                    <i class="fas fa-list-check text-xl group-hover:scale-110 transition-transform"></i>
                    <span>Tâches</span>
                </button>

                <button @click="activeTab = 'fichiers'; localStorage.setItem('tab', 'fichiers')"
                        :class="{ 'text-teal-600 border-teal-600': activeTab === 'fichiers', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'fichiers' }"
                        class="flex items-center space-x-2 py-4 md:py-6 px-2 md:px-4 border-b-2 font-medium transition-all duration-300 group whitespace-nowrap">
                    <i class="fas fa-folder-open text-xl group-hover:scale-110 transition-transform"></i>
                    <span>Documents</span>
                </button>

                <button @click="activeTab = 'membres'; localStorage.setItem('tab', 'membres')"
                        :class="{ 'text-teal-600 border-teal-600': activeTab === 'membres', 'text-gray-500 hover:text-gray-700 border-transparent': activeTab !== 'membres' }"
                        class="flex items-center space-x-2 py-4 md:py-6 px-2 md:px-4 border-b-2 font-medium transition-all duration-300 group whitespace-nowrap">
                    <i class="fas fa-user-group text-xl group-hover:scale-110 transition-transform"></i>
                    <span>Équipe</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content with animation -->
        <div class="p-4 md:p-8">


<!-- Grille des tâches -->
<div x-show="activeTab === 'taches'" >
    <div class="flex justify-between items-center mb-4 md:mb-8">
        <h3 class="text-xl md:text-2xl font-bold text-gray-800">Taches du projet</h3>
        <button @click="opentache = !opentache" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Ajouter une tache</span>
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    
        @foreach($tasks as $task)
            @php
                $task->members = $task->users->pluck('id')->toArray();
            @endphp
            <div class="group bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-300
                        hover:translate-y-[-2px] border border-gray-100 relative overflow-hidden">
                <!-- Barre de décoration -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r 
                    @if($task->status == 'en cours') from-amber-400 to-amber-500
                    @elseif($task->status == 'terminée') from-emerald-400 to-emerald-500
                    @else from-gray-300 to-gray-400 @endif">
                </div>
    
                <!-- Contenu de la tâche -->
                <div class="flex justify-between items-start mb-4 pt-2">
                    <div class="flex-1 mr-2 min-w-0"> <!-- Ajout de min-w-0 pour éviter les débordements -->
                        <h4 class="text-lg font-semibold text-gray-800 overflow-wrap break-word line-clamp-2 group-hover:text-teal-600 transition-colors">
                            {{ $task->title }}
                        </h4>
                        <p class="text-lg text-gray-600 line-clamp-2 leading-relaxed mt-1">{{ $task->description }}</p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Status Badge -->
                        <span class="px-2 py-1 text-xs font-medium rounded-lg whitespace-nowrap shadow-sm
                            @if($task->status == 'en cours') bg-gradient-to-r from-amber-100 to-amber-50 text-amber-800 border border-amber-200
                            @elseif($task->status == 'terminée') bg-gradient-to-r from-emerald-100 to-emerald-50 text-emerald-800 border border-emerald-200
                            @else bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 border border-gray-200 @endif">
                            {{ $task->status }}
                        </span>
    
                        <!-- Menu Actions -->
                        <div class="relative">
                            <button onclick="toggleDropdown('dropdown-{{ $task->id }}')" 
                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-50 
                                        transition-colors border border-transparent hover:border-gray-100">
                                <i class="fas fa-ellipsis-v text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                            </button>
                            <div id="dropdown-{{ $task->id }}" 
                                class="dropdown-menu hidden absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg 
                                        border border-gray-100 z-10 overflow-hidden transform origin-top-right transition-all duration-200">
                                <button @click="event.stopPropagation(); $dispatch('open-edit-modal', @js($task)); open = false;" class="w-full flex items-center px-3 py-2 text-md text-gray-700 hover:bg-gray-50 
                                                                transition-colors group">
                                    <i class="fas fa-edit mr-2 text-gray-400 group-hover:text-teal-500 transition-colors"></i>
                                    Modifier
                                </button>
                                <form action="{{ route('projects.tasks.destroy', ['project' => $project->id, 'task' => $task->id]) }}" method="POST" 
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full flex items-center px-3 py-2 text-md text-red-600 hover:bg-red-50 
                                                transition-colors group">
                                        <i class="fas fa-trash-alt mr-2 text-red-400 group-hover:text-red-500 transition-colors"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
    
                <!-- Footer avec date et utilisateurs -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm mb-2 sm:mb-0">
                        <div class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-50 
                                group-hover:bg-teal-50 transition-colors">
                            <i class="fas fa-clock text-gray-400 group-hover:text-teal-500 transition-colors"></i>
                        </div>
                        <span class="text-gray-500">Echéance: {{ \Carbon\Carbon::parse($task->deadline)->format('d M') }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($task->users as $user)
                            <div class="px-2 py-1 bg-gray-50 text-xs font-medium text-gray-700 rounded-lg 
                                    border border-gray-200 hover:bg-teal-50 hover:text-teal-700 hover:border-teal-200 
                                    transition-all duration-200">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-user text-gray-400"></i>
                                    {{$user->name}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const allDropdowns = document.querySelectorAll('.dropdown-menu');
            
            // Ferme tous les autres menus avec animation
            allDropdowns.forEach(menu => {
                if (menu.id !== id && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
    
            // Toggle avec animation
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.classList.add('scale-100', 'opacity-100');
            } else {
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 100);
            }
        }
    
        // Ferme les menus lors d'un clic à l'extérieur
        document.addEventListener('click', (event) => {
            const isDropdownClick = event.target.closest('.dropdown-menu') || 
                                event.target.closest('button');
            
            if (!isDropdownClick) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });
    </script>
</div>


            <!-- Members Tab -->
            <div x-show="activeTab === 'membres'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-6 mb-5">

                 
                 <div class="flex justify-between items-center mb-4 md:mb-8">
                    <h3 class="text-xl md:text-2xl font-bold text-gray-800">Membres du projet</h3>
                    @if ($project->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists())
                    <button @click="openmember = !openmember" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Ajouter un membre</span>
                    </button>
                    @endif
                </div>
                

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                    @foreach($members as $user)
                        <div class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <img class="w-12 h-12 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" alt="{{ $user->name }}">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->pivot->fonction }} - {{ ucfirst($user->pivot->role) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>                
            </div>
              <!-- Documents Tab -->
              <div x-show="activeTab === 'fichiers'"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="space-y-6">

    <div class="flex justify-between items-center mb-4 md:mb-8">
        <h3 class="text-xl md:text-2xl font-bold text-gray-800">Documents du projet</h3>
        <button @click="openfilemodal = !openfilemodal"
                class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Ajouter un document</span>
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        @foreach($documents as $document)
            <div class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100 hover:bg-gray-50 transition-colors relative">
                <div class="flex items-center space-x-4">
                    <!-- Icône en fonction de l'extension du fichier -->
                    @if(str_contains($document->file_path, '.pdf'))
                        <i class="fas fa-file-pdf text-3xl text-red-500"></i>
                    @elseif(str_contains($document->file_path, '.docx'))
                        <i class="fas fa-file-word text-3xl text-blue-500"></i>
                    @elseif(str_contains($document->file_path, '.xlsx'))
                        <i class="fas fa-file-excel text-3xl text-green-500"></i>
                    @else
                        <i class="fas fa-file-image text-3xl text-teal-500"></i>
                    @endif
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $document->title }}</h4>
                    </div>
                </div>
    
                <!-- Menu déroulant -->
                <div class="absolute top-4 right-4">
                    <div x-data="{ openfilemenu: false }" class="relative">
                        <button @click="openfilemenu = !openfilemenu" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-ellipsis-v text-lg"></i> <!-- Icone menu -->
                        </button>
                        <div x-show="openfilemenu" @click.outside="openfilemenu = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg z-10">
                            <ul class="py-2 text-gray-700">
                                <li>
                                    <a href="{{ Storage::url($document->file_path) }}" class="block px-4 py-2 text-sm hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-download mr-2"></i> Télécharger
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('projects.documents.destroy', ['project' => $project, 'document' => $document]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors">
                                            <i class="fas fa-trash-alt mr-2"></i> Supprimer
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if ($documents->isEmpty())

    @endif
    <div class="h-5">

    </div>
</div>

        </div>
    </div>
</div>



<!-- Modal tache-->
<div x-show="opentache"
     x-cloak
     @keydown.escape.window="opentache = false"
     @open-edit-modal.window="initForm($event.detail)"
     class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
     aria-modal="true"
     role="dialog"
     aria-labelledby="modal-title">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6 md:p-8"
             @click.away="opentache = false">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800" id="modal-title" x-text="isEditing ? 'Modifier la tâche' : 'Ajouter une tâche'">
                    Ajouter une tâche
                </h2>
                <button @click="opentache = false" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="task-form" method="POST" action="{{ route('projects.tasks.store', $project) }}" class="space-y-4">
                @csrf
                <input type="hidden" id="task-method" name="_method" value="POST">
            
                <div>
                    <label for="task-title" class="block text-sm font-medium text-gray-700">Titre de la tâche</label>
                    <div class="mt-1">
                        <input type="text"
                               id="task-title"
                               name="title"
                               required
                               class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="e.g., Nom de la tache"
                               value="{{ old('title') }}">
                    </div>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <div>
                    <label for="task-description" class="block text-sm font-medium text-gray-700">Description</label>
                    <div class="mt-1">
                        <textarea id="task-description"
                                  name="description"
                                  rows="3"
                                  class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                  placeholder="Description">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Deadline</label>
                        <div class="mt-1">
                            <input type="date"
                                   id="task-due_date"
                                   name="due_date"
                                   required
                                   class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                   value="{{ old('due_date') }}">
                        </div>
                        @error('due_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <label for="task-status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <div class="mt-1">
                            <select id="task-status" name="status" required class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="en cours" {{ old('status') == 'en cours' ? 'selected' : '' }}>En cours</option>
                                <option value="suspendue" {{ old('status') == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
                                <option value="terminée" {{ old('status') == 'terminée' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="users" class="block text-sm font-medium text-gray-700">
                        Sélectionner les membres à qui cette tâche est assignée
                    </label>
                    
                    <div class="mt-1">
                        <select 
                            id="users" 
                            name="users[]" 
                            multiple 
                            required 
                            class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block h-20 w-full sm:text-sm border-gray-300 rounded-md"
                        >
                            @foreach($members ?? [] as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->pivot->fonction }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    @error('users')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const select = document.getElementById("users");
                
                        select.addEventListener("mousedown", function (e) {
                            e.preventDefault();
                            const option = e.target;
                
                            if (option.tagName === "OPTION") {
                                option.selected = !option.selected;
                            }
                        });
                    });
                </script>
                                
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="opentache = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl">
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


<!-- Modal Member -->
<div x-show="openmember"
    x-cloak
    @keydown.escape.window="openmember = false"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
    aria-modal="true"
    role="dialog"
    aria-labelledby="modal-title">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6 md:p-8"
            @click.away="openmember = false">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800" id="modal-title" x-text="isEditing ? 'Modifier le membre' : 'Ajouter un membre'">
                    Ajouter un membre
                </h2>
                <button @click="openmember = false" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Afficher les erreurs globales -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="member-form" method="POST" action="{{ route('projects.addUser', $project) }}" class="space-y-4">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="john.doe@example.com">
                    </div>
                    <!-- Afficher l'erreur pour l'email -->
                    @error('email')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Fonction Field -->
                <div>
                    <label for="fonction" class="block text-sm font-medium text-gray-700">Fonction</label>
                    <div class="mt-1">
                        <input type="text"
                               id="fonction"
                               name="fonction"
                               value="{{ old('fonction') }}"
                               class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="e.g., Développeur Frontend">
                    </div>
                    @error('fonction')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Permissions</label>
                    <div class="mt-1">
                        <select id="status" name="role" required class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="membre" {{ old('status') == 'membre' ? 'selected' : '' }}>Membre</option>
                            <option value="admin" {{ old('status') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                    </div>
                    @error('status')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openmember = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl">
                        Annuler
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-xl">
                        <span x-text="isEditing ? 'Mettre à jour' : 'Enregistrer'">Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'ajout de document -->
<div x-show="openfilemodal"
     x-cloak
     @keydown.escape.window="openfilemodal = false"
     class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50"
     aria-modal="true"
     role="dialog"
     aria-labelledby="modal-title">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6 md:p-8"
             @click.away="openfilemodal = false">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800" id="modal-title">
                    Ajouter un document
                </h2>
                <button @click="openfilemodal = false" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('projects.documents.store', $project) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="titlefile" class="block text-sm font-medium text-gray-700">Nom du document</label>
                    <div class="mt-1">
                        <input type="text"
                               id="titlefile"
                               name="titlefile"
                               required
                               class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md"
                               placeholder="Nom du document">
                    </div>
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Sélectionner un fichier</label>
                    <div class="mt-1">
                        <input type="file"
                               id="file"
                               name="file"
                               required
                               class="shadow-sm focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="openfilemodal = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl">
                        Annuler
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-xl">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function() {
    @if (!$errors->any())
        opentache = true;
    @endif
});
</script>
</div>
@endsection

