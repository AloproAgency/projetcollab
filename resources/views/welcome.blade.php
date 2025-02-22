<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bienvenue - Gestion de Projet Collaboratif</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
     <link rel="preconnect" href="https://fonts.bunny.net">
     <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

     <!-- Styles / Scripts -->
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <nav class="animated-gradient p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-bold flex items-center">
                <i class="fas fa-project-diagram mr-2"></i>
                ProjetCollab
            </a>
            <div>
                @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a
                                        href="{{ url('/dashboard') }}"
                                        class="rounded-md px-3 py-2 text-white hover:text-white/80 focus-visible:ring-white"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-md px-3 py-2 text-white hover:text-white/80 focus-visible:ring-white"
                                    >
                                    <i class="fa fa-sign-in" aria-hidden="true"></i>Connexion
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="rounded-md px-3 py-2 text-white hover:text-white/80 focus-visible:ring-white"
                                        >
                                        <i class="fa fa-user" aria-hidden="true"></i>Inscription
                                        </a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="animated-gradient py-32">
        <div class="container mx-auto text-center px-4">
            <h1 class="text-5xl font-bold text-white">Bienvenue sur ProjetCollab</h1>
            <p class="text-xl text-gray-200 mt-4">La plateforme ultime pour gérer vos projets en équipe.</p>
            <div class="mt-8">
                <a href="{{ route('register') }}" class="bg-white text-teal-700 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 hover:scale-105 transition-transform">
                    Commencer Maintenant <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <!-- Texte animé -->
            <div class="mt-12">
                <p id="typewriter" class="typewriter text-2xl font-semibold text-white"></p>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800">Fonctionnalités</h2>
            <p class="text-gray-600 mt-4">Découvrez les fonctionnalités qui rendent ProjetCollab unique.</p>
        </div>
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform">
                <div class="text-center">
                    <i class="fas fa-tasks text-5xl text-teal-600"></i>
                    <h3 class="text-xl font-bold text-gray-800 mt-4">Gestion de Tâches</h3>
                    <p class="text-gray-600 mt-2">Créez, assignez et suivez vos tâches en temps réel.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform">
                <div class="text-center">
                    <i class="fas fa-users text-5xl text-teal-600"></i>
                    <h3 class="text-xl font-bold text-gray-800 mt-4">Collaboration en Équipe</h3>
                    <p class="text-gray-600 mt-2">Travaillez en équipe de manière fluide et efficace.</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg hover:scale-105 transition-transform">
                <div class="text-center">
                    <i class="fas fa-chart-line text-5xl text-teal-600"></i>
                    <h3 class="text-xl font-bold text-gray-800 mt-4">Rapports et Analyses</h3>
                    <p class="text-gray-600 mt-2">Générez des rapports détaillés pour suivre vos progrès.</p>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Call to Action Section -->
    <div class="animated-gradient py-20">
        <div class="container mx-auto text-center px-4">
            <h2 class="text-4xl font-bold text-white">Prêt à Transformer Votre Gestion de Projet ?</h2>
            <p class="text-xl text-gray-200 mt-4">Rejoignez des milliers d'équipes qui utilisent déjà ProjetCollab.</p>
            <div class="mt-8">
                <a href="{{ route('register') }}" class="bg-white text-teal-700 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 hover:scale-105 transition-transform">
                    S'inscrire Maintenant <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-teal-900 text-white py-8">
        <div class="container mx-auto text-center">
            <p>&copy; 2023 ProjetCollab. Open Source avec <i class="fas fa-heart text-red-500"></i> par <a href="https://alopro.net" class="font-semibold hover:underline">NOUGBOLOGNI A. Valentin</a>
            </p>
        </div>
    </footer>

    <!-- Script for Typewriter Effect -->
    <script>
        const typewriterTexts = [
            "Gérer vos tâches efficacement.",
            "Collaborer en temps réel avec votre équipe.",
            "Suivre vos progrès avec des rapports détaillés.",
            "Planifier vos projets avec précision.",
            "Partager des fichiers en toute simplicité."
        ];
        let index = 0;
        let textIndex = 0;
        let isDeleting = false;

        function typeWriter() {
            const currentText = typewriterTexts[index];
            const typewriterElement = document.getElementById("typewriter");

            if (isDeleting) {
                typewriterElement.textContent = currentText.substring(0, textIndex - 1);
                textIndex--;
            } else {
                typewriterElement.textContent = currentText.substring(0, textIndex + 1);
                textIndex++;
            }

            if (!isDeleting && textIndex === currentText.length) {
                isDeleting = true;
                setTimeout(typeWriter, 2000); // Pause avant de commencer à effacer
            } else if (isDeleting && textIndex === 0) {
                isDeleting = false;
                index = (index + 1) % typewriterTexts.length; // Passer au texte suivant
                setTimeout(typeWriter, 500); // Pause avant de commencer à écrire
            } else {
                setTimeout(typeWriter, isDeleting ? 50 : 100); // Vitesse d'écriture/effacement
            }
        }

        // Démarrer l'animation
        typeWriter();
    </script>

</body>
</html>
