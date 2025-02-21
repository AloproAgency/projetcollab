<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Responsive</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Toggle -->
    <button id="mobile-menu-toggle" class="fixed top-4 right-4 z-50 bg-white p-2 rounded-lg shadow-lg md:hidden">
        <i class="fas fa-bars text-gray-600"></i>
    </button>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full bg-white shadow-md z-40">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-4 flex items-center justify-between border-b">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 animated-gradient rounded-lg flex items-center justify-center">
                        <i class="fas fa-project-diagram mr-2 text-white"></i>
                    </div>
                    <span class="logo-text font-bold text-xl text-teal-800">ProjetCollab</span>
                </div>
                <button id="toggle-sidebar" class="p-2 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-bars text-gray-600"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <div class="space-y-2">
                    <a href="/dashboard" class="flex items-center gap-3 p-3 rounded-lg {{ $page == 'dashboard' ? 'bg-teal-100 text-teal-600' : 'hover:bg-gray-50 text-gray-600' }}">
                        <i class="fas fa-home text-lg"></i>
                        <span class="sidebar-text">Accueil</span>
                    </a>
                    <a href="/projects" class="flex items-center gap-3 p-3 rounded-lg {{ $page == 'projects' ? 'bg-teal-100 text-teal-600' : 'hover:bg-gray-50 text-gray-600' }}">
                        <i class="fas fa-tasks text-lg"></i>
                        <span class="sidebar-text">Projets</span>
                    </a>
                    <a href="/teams" class="flex items-center gap-3 p-3 rounded-lg {{ $page == 'teams' ? 'bg-teal-100 text-teal-600' : 'hover:bg-gray-50 text-gray-600' }}">
                        <i class="fas fa-users text-lg"></i>
                        <span class="sidebar-text">Mon équipe</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t">
                <div class="flex items-center gap-3">
                    <div class="rounded-full w-10 h-10 bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-user text-gray-500"></i>
                    </div>
                    <div class="sidebar-text">
                        <p class="font-medium">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <i class="fas fa-sign-out text-gray-500"></i>
                            <button type="submit" class="text-sm text-gray-500 hover:underline">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="main-content" class="main-content min-h-screen flex flex-col">
        <!-- Header -->
        <header class="px-10 py-2 bg-white shadow-sm flex-16">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
                    <p class="text-gray-500">Bienvenue, {{ Auth::user()->name }}</p>
                </div>
                <div class="flex items-center gap-4">
                    
                    <button class="p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                </div>
            </div>
        </header>

<div class="px-6 shadow-sm mb-2 h-screen flex-0 bg-gray-150">
    @if (session('success'))
        <div x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 8000)" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
            class="fixed bottom-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg flex items-center justify-between">
                <div>
                    <strong class="font-bold">Succès!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="ml-4 text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif
    @if (@session('error'))
        <div x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 8000)" 
            x-show="show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-4"
            class="fixed bottom-4 right-4 z-50">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg flex items-center justify-between">
                <div>
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="ml-4 text-red-700 hover:text-red-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="container mx-auto pt-5">
        @yield('content')
    </div>
</div>
</main>

<script>
    // Toggle sidebar
    const toggleBtn = document.getElementById('toggle-sidebar');
    const mobileToggleBtn = document.getElementById('mobile-menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');

    // Desktop toggle
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('collapsed');
    });

    // Mobile toggle
    mobileToggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-open');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && 
                !mobileToggleBtn.contains(e.target) && 
                sidebar.classList.contains('mobile-open')) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
