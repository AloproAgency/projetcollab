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
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg bg-teal-50 text-teal-600">
                        <i class="fas fa-home text-lg"></i>
                        <span class="sidebar-text">Accueil</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-600">
                        <i class="fas fa-tasks text-lg"></i>
                        <span class="sidebar-text">Projets</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 text-gray-600">
                        <i class="fas fa-users text-lg"></i>
                        <span class="sidebar-text">Équipe</span>
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
                    <div class="relative flex-1 md:flex-initial">
                        <input type="text" 
                               placeholder="Rechercher..." 
                               class="w-full md:w-64 pl-10 pr-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button class="p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                </div>
            </div>
        </header>

<div class="p-6 shadow-sm mb-10 h-screen flex-0 bg-gray-150">
    @if (session('success'))
        <div class="container mx-auto px-6 py-12">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @yield('content')
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
</body>
</html>
