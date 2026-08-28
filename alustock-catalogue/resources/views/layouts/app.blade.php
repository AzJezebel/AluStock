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
                        ink: {
                            50:  '#f7f5f3',
                            100: '#eeebe7',
                            200: '#ddd6cf',
                            300: '#c2b8ac',
                            400: '#9c8f80',
                            500: '#786c5e',
                            600: '#5c5245',
                            700: '#453d33',
                            800: '#2e2822',
                            900: '#1c1713',
                            950: '#0f0c0a',
                        },
                    }
                }
            }
        }
    </script>

    {{-- Style global pour le sticky footer --}}
    <style>
        /* Structure principale : body en flex column avec min-height 100vh */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Le contenu principal prend tout l'espace disponible */
        .main-content {
            flex: 1 0 auto;
        }

        /* Le footer reste en bas */
        .main-footer {
            flex-shrink: 0;
        }

        /* Pour la navigation sticky */
        .nav-sticky {
            position: sticky;
            top: 0;
            z-index: 40;
        }

        /* Sidebar sticky */
        .sidebar-sticky {
            position: sticky;
            top: 80px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }

        /* Scrollbar hide pour la navigation */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Sur mobile, le sidebar n'est pas sticky */
        @media (max-width: 768px) {
            .sidebar-sticky {
                position: relative;
                top: 0;
                max-height: none;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ============================================================
         BANDEAU UTILITAIRE
         ============================================================ --}}
    <div class="bg-ink-950 text-ink-300 text-xs flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-between">
            <span class="hidden sm:inline truncate">Catalogue de référence — aluminium industriel et profilés structuraux</span>
            <div class="flex items-center gap-4 ml-auto">
                <a href="#" class="hover:text-white transition">Documentation technique</a>
                <a href="#" class="hover:text-white transition">Contact</a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         EN-TÊTE
         ============================================================ --}}
    @hasSection('hero')
        <header class="bg-ink-900 flex-shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
                @include('partials.logo', ['dark' => true])

                <a href="#" class="hidden sm:inline-flex items-center px-4 py-2 border border-white/20 text-white text-sm font-medium rounded-lg hover:bg-white/10 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catalogue PDF
                </a>
            </div>

            @yield('hero')
        </header>
    @else
        <header class="bg-white border-b border-ink-200 flex-shrink-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row md:items-center gap-4">
                @include('partials.logo')

                <form action="{{ route('search.index') }}" method="GET" class="relative flex-1 max-w-2xl mx-auto w-full">
                    <input type="text"
                           name="q"
                           placeholder="Rechercher par référence, alliage, dimension..."
                           class="w-full px-4 py-2.5 bg-ink-50 border border-ink-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-transparent transition">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-400 hover:text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </form>

                <a href="#" class="inline-flex items-center justify-center px-4 py-2 bg-amber-700 text-white text-sm font-medium rounded-lg hover:bg-amber-800 transition whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catalogue PDF
                </a>
            </div>
        </header>
    @endif

    {{-- ============================================================
         CONTENU PRINCIPAL AVEC SIDEBAR
         ============================================================ --}}
    <div class="main-content flex flex-col md:flex-row max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6 w-full">
        
        {{-- SIDEBAR GAUCHE --}}
        <aside class="md:w-64 lg:w-72 flex-shrink-0">
            <div class="sidebar-sticky bg-white rounded-xl shadow-sm border border-ink-200 p-3">
                
                {{-- Bouton de bascule mobile --}}
                <button class="md:hidden w-full flex items-center justify-between text-left text-sm font-medium text-ink-700 p-2 hover:bg-ink-50 rounded-lg" 
                        onclick="document.getElementById('sidebar-menu').classList.toggle('hidden')">
                    <span>Menu</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Menu --}}
                <nav id="sidebar-menu" class="hidden md:block mt-2 space-y-1 text-sm">
                    @include('partials.sidebar-menu')
                </nav>
            </div>
        </aside>

        {{-- CONTENU PRINCIPAL --}}
        <main class="flex-1 min-w-0">
            {{-- Fil d'Ariane (si défini) --}}
            @hasSection('breadcrumb')
                <div class="mb-4 text-sm text-ink-500">
                    @yield('breadcrumb')
                </div>
            @endif

            {{-- Messages flash --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 rounded" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            {{-- Contenu de la page --}}
            @yield('content')
        </main>
    </div>

    {{-- ============================================================
         PIED DE PAGE (toujours en bas)
         ============================================================ --}}
    <footer class="main-footer bg-ink-950 text-ink-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <span class="text-lg font-bold text-white">Alu<span class="text-amber-500">Stock</span></span>
                    <p class="text-sm text-ink-400 mt-2">Distribution industrielle d'aluminium et profilés structuraux depuis 2024.</p>
                </div>
                <div>
                    <h4 class="text-white font-medium text-sm uppercase tracking-wider">Navigation</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        @foreach(($navCategories ?? []) as $navCategory)
                            <li><a href="{{ route('ouvrages.index', ['categorie' => $navCategory->slug]) }}" class="hover:text-white transition">{{ $navCategory->nom }}</a></li>
                        @endforeach
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
                        <li class="text-ink-400">contact@alustock.fr</li>
                        <li class="text-ink-400">+33 (0)1 23 45 67 89</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-ink-800 mt-6 pt-4 text-center text-sm text-ink-500">
                &copy; {{ date('Y') }} AluStock. Tous droits réservés. Catalogue de référence — aluminium industriel et profilés structuraux.
            </div>
        </div>
    </footer>

    {{-- ============================================================
         SCRIPTS
         ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation scrollbar hide
            const nav = document.querySelector('nav .overflow-x-auto');
            if (nav) {
                nav.classList.add('scrollbar-hide');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>