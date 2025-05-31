<?php
// ./index.php
include 'pdo.php';
session_start();
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Pro | Gestion de Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #bae6fd 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">
    <!-- Hero Section -->
    <section class="hero-gradient py-16 md:py-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4 text-gray-900">Gérez votre portfolio professionnel avec simplicité</h1>
                    <p class="text-xl text-gray-700 mb-8">Une solution complète pour organiser, présenter et partager vos projets et compétences.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-lg shadow-md text-center transition-all">
                            Commencer maintenant
                        </a>
                        <a href="#" class="border border-primary-500 text-primary-600 hover:bg-primary-50 font-medium py-3 px-6 rounded-lg shadow-sm text-center transition-all">
                            Voir la démo
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <img src="https://illustrations.popsy.co/amber/digital-nomad.svg" alt="Portfolio Management" class="w-full max-w-md">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Fonctionnalités puissantes</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Tout ce dont vous avez besoin pour gérer efficacement votre portfolio professionnel</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 card-hover transition-all">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Gestion de projets</h3>
                    <p class="text-gray-600">Organisez et présentez vos projets avec des détails complets, images et technologies utilisées.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 card-hover transition-all">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Compétences techniques</h3>
                    <p class="text-gray-600">Catégorisez vos compétences par niveau et domaine pour une présentation claire.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 card-hover transition-all">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Accès sécurisé</h3>
                    <p class="text-gray-600">Gestion des utilisateurs avec différents niveaux d'accès pour une collaboration en équipe.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold text-primary-600 mb-2">1,200+</div>
                    <div class="text-gray-600">Projets gérés</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold text-primary-600 mb-2">850+</div>
                    <div class="text-gray-600">Utilisateurs actifs</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold text-primary-600 mb-2">50+</div>
                    <div class="text-gray-600">Technologies supportées</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-3xl font-bold text-primary-600 mb-2">24/7</div>
                    <div class="text-gray-600">Support disponible</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Ce que nos utilisateurs disent</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Découvrez comment Portfolio Pro a transformé la gestion de leurs projets</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div class="bg-gray-50 p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold mr-4">JD</div>
                        <div>
                            <h4 class="font-semibold">Jean Dupont</h4>
                            <p class="text-gray-600 text-sm">Développeur Fullstack</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"Portfolio Pro m'a permis de centraliser tous mes projets en un seul endroit. La présentation est professionnelle et j'ai reçu plusieurs propositions grâce à cela."</p>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold mr-4">SM</div>
                        <div>
                            <h4 class="font-semibold">Sophie Martin</h4>
                            <p class="text-gray-600 text-sm">Designer UI/UX</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"En tant que designer, pouvoir organiser mes projets avec des visuels et des détails techniques est essentiel. Cette plateforme a simplifié mon workflow."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary-600 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-6">Prêt à transformer votre portfolio ?</h2>
            <p class="text-primary-100 max-w-2xl mx-auto mb-8">Rejoignez des centaines de professionnels qui utilisent déjà Portfolio Pro pour présenter leur travail au monde.</p>
            <a href="#" class="inline-block bg-white text-primary-600 hover:bg-gray-100 font-medium py-3 px-8 rounded-lg shadow-md transition-all">
                Créer un compte gratuit
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Portfolio Pro</h3>
                    <p class="mb-4">La solution ultime pour gérer et présenter votre travail professionnel.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Solutions</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">Pour développeurs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pour designers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pour freelances</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pour entreprises</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutoriels</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white text-lg font-semibold mb-4">Entreprise</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white transition-colors">À propos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Carrières</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Presse</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-sm text-center">
                <p>&copy; 2023 Portfolio Pro. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>