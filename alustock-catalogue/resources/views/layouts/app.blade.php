{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AluStock - Catalogue de référence aluminium industriel')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

    <!-- Styles (Tailwind via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        aluminum: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-aluminum-50 text-aluminum-800">

    {{-- ============================================================
         HEADER INDUSTRIEL
         ============================================================ --}}
    <header class="bg-white border-b border-aluminum-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between py-4 gap-4">
                
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                            A
                        </div>
                        <div>
                            <span class="text-xl font-bold text-aluminum-900 tracking-tight">Alu<span class="text-primary-600">Stock</span></span>
                            <span class="block text-[10px] uppercase tracking-widest text-aluminum-400 font-medium">Distribution industrielle</span>
                        </div>
                    </a>
                </div>

                {{-- Barre de recherche --}}
                <div class="flex-1 max-w-2xl mx-auto md:mx-4 w-full">
                    <form action="#" method="GET" class="relative">
                        <input type="text" 
                               name="q" 
                               placeholder="Rechercher par référence, alliage, dimension..." 
                               class="w-full px-4 py-2.5 bg-aluminum-50 border border-aluminum-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-aluminum-400 hover:text-primary-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- Actions --}}
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-aluminum-400 hover:text-primary-600 transition text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                    @auth
                        <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-800 transition">
                            Admin
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- ============================================================
         NAVIGATION PRINCIPALE
         ============================================================ --}}
    <nav class="bg-white border-b border-aluminum-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1 md:space-x-6 overflow-x-auto py-3 scrollbar-hide">
                <a href="{{ route('home') }}" 
                   class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('home') ? 'text-primary-600' : 'text-aluminum-600 hover:text-primary-600' }} transition">
                    Accueil
                </a>
                <a href="{{ route('gammes.index') }}" 
                   class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('gammes.*') ? 'text-primary-600' : 'text-aluminum-600 hover:text-primary-600' }} transition">
                    Gammes
                </a>
                <a href="#" 
                   class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('categories.*') ? 'text-primary-600' : 'text-aluminum-600 hover:text-primary-600' }} transition">
                    Catégories
                </a>
                <a href="#" 
                   class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('ouvrages.*') ? 'text-primary-600' : 'text-aluminum-600 hover:text-primary-600' }} transition">
                    Ouvrages
                </a>
                <a href="#" 
                   class="whitespace-nowrap text-sm font-medium {{ request()->routeIs('composants.*') ? 'text-primary-600' : 'text-aluminum-600 hover:text-primary-600' }} transition">
                    Composants
                </a>
                <span class="text-aluminum-300">|</span>
                <a href="#" 
                   class="whitespace-nowrap text-sm font-medium text-aluminum-600 hover:text-primary-600 transition">
                    🔍 Recherche
                </a>
            </div>
        </div>
    </nav>

    {{-- ============================================================
         CONTENU PRINCIPAL
         ============================================================ --}}
    <main class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Messages flash --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 rounded" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            {{-- Entête de page --}}
            @hasSection('page-header')
                @yield('page-header')
            @else
                @hasSection('page-title')
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-aluminum-900 tracking-tight">@yield('page-title')</h1>
                        @hasSection('page-subtitle')
                            <p class="text-aluminum-500 mt-1">@yield('page-subtitle')</p>
                        @endif
                    </div>
                @endif
            @endif

            {{-- Contenu de la page --}}
            @yield('content')
        </div>
    </main>

    {{-- ============================================================
         PIED DE PAGE INDUSTRIEL
         ============================================================ --}}
    <footer class="bg-aluminum-900 text-aluminum-300 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <span class="text-lg font-bold text-white">Alu<span class="text-primary-400">Stock</span></span>
                    <p class="text-sm text-aluminum-400 mt-2">Distribution industrielle d'aluminium et profilés structuraux depuis xxxx.</p>
                </div>
                <div>
                    <h4 class="text-white font-medium text-sm uppercase tracking-wider">Navigation</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li><a href="{{ route('gammes.index') }}" class="hover:text-white transition">Gammes</a></li>
                        <li><a href="#" class="hover:text-white transition">Catégories</a></li>
                        <li><a href="#" class="hover:text-white transition">Ouvrages</a></li>
                        <li><a href="#" class="hover:text-white transition">Composants</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium text-sm uppercase tracking-wider">Légal</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li><a href="#" class="hover:text-white transition">Mentions légales</a></li>
                        <li><a href="#" class="hover:text-white transition">Confidentialité</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-medium text-sm uppercase tracking-wider">Contact</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li class="text-aluminum-400">contact@alustock.fr</li>
                        <li class="text-aluminum-400">0696 96 96 96</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-aluminum-800 mt-6 pt-4 text-center text-sm text-aluminum-500">
                &copy; {{ date('Y') }} AluStock. Tous droits réservés. Catalogue de référence — aluminium industriel et profilés structuraux.
            </div>
        </div>
    </footer>

    {{-- ============================================================
         SCRIPTS
         ============================================================ --}}
    <script>
        // Scrollbar hide pour la navigation
        document.addEventListener('DOMContentLoaded', function() {
            const nav = document.querySelector('nav .overflow-x-auto');
            if (nav) {
                nav.classList.add('scrollbar-hide');
            }
        });
    </script>
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @stack('scripts')
</body>
</html>