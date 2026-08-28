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

        /* Styles pour l'autocomplétion */
        #search-results {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
        }
        #search-results::-webkit-scrollbar {
            width: 4px;
        }
        #search-results::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        #search-results::-webkit-scrollbar-thumb {
            background: #c2b8ac;
            border-radius: 4px;
        }
        #search-results::-webkit-scrollbar-thumb:hover {
            background: #9c8f80;
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

                {{-- Barre de recherche avec autocomplétion --}}
                <form action="{{ route('search.index') }}" method="GET" class="relative flex-1 max-w-2xl mx-auto w-full" id="search-form">
                    <input type="text"
                           name="q"
                           id="search-input"
                           placeholder="Rechercher par référence, alliage, dimension..."
                           class="w-full px-4 py-2.5 bg-ink-50 border border-ink-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-transparent transition"
                           autocomplete="off">
                    
                    {{-- Résultats autocomplétion --}}
                    <div id="search-results" class="absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-lg border border-ink-200 overflow-hidden hidden z-50">
                        <div id="search-results-list" class="divide-y divide-ink-100 max-h-80 overflow-y-auto"></div>
                        <div class="px-4 py-2 bg-ink-50 text-xs text-ink-400 text-center border-t border-ink-100">
                            Appuyez sur Entrée pour voir tous les résultats
                        </div>
                    </div>

                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-ink-400 hover:text-amber-700 transition">
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
         SCRIPTS (inclut l'autocomplétion)
         ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation scrollbar hide
            const nav = document.querySelector('nav .overflow-x-auto');
            if (nav) {
                nav.classList.add('scrollbar-hide');
            }

            // ============================================================
            // AUTOCOMPLÉTION — barre de recherche
            // ============================================================
            const searchInput = document.getElementById('search-input');
            const resultsContainer = document.getElementById('search-results');
            const resultsList = document.getElementById('search-results-list');
            let searchTimeout = null;
            let isNavigating = false;

            if (!searchInput || !resultsContainer || !resultsList) {
                return;
            }

            // Fonction pour formater les résultats
            function renderResults(data) {
                if (data.length === 0) {
                    resultsList.innerHTML = `
                        <div class="px-4 py-4 text-sm text-ink-400 text-center">
                            Aucun résultat trouvé pour "<span class="font-medium text-ink-600">${searchInput.value.trim()}</span>"
                        </div>
                    `;
                    resultsContainer.classList.remove('hidden');
                    return;
                }

                let html = '';
                data.forEach(item => {
                    const badgeColor = item.type === 'ouvrage' 
                        ? 'bg-amber-100 text-amber-700' 
                        : 'bg-blue-100 text-blue-700';
                    
                    const icon = item.type === 'ouvrage' 
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>' 
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>';
                    
                    html += `
                        <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-ink-50 transition group">
                            <div class="w-8 h-8 flex-shrink-0 rounded-lg bg-ink-100 flex items-center justify-center text-ink-400 group-hover:bg-amber-100 group-hover:text-amber-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${icon}
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-ink-900 group-hover:text-amber-700 transition truncate">
                                        ${item.label}
                                    </span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium ${badgeColor} flex-shrink-0">
                                        ${item.badge}
                                    </span>
                                </div>
                                <span class="text-xs text-ink-400">Réf. ${item.reference}</span>
                            </div>
                            <svg class="w-4 h-4 text-ink-300 group-hover:text-amber-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    `;
                });

                resultsList.innerHTML = html;
                resultsContainer.classList.remove('hidden');
            }

            // Gestion de l'input
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    resultsContainer.classList.add('hidden');
                    return;
                }

                searchTimeout = setTimeout(function() {
                    fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur réseau');
                            }
                            return response.json();
                        })
                        .then(data => {
                            renderResults(data);
                        })
                        .catch(() => {
                            resultsList.innerHTML = `
                                <div class="px-4 py-4 text-sm text-red-400 text-center">
                                    Une erreur est survenue lors de la recherche.
                                </div>
                            `;
                            resultsContainer.classList.remove('hidden');
                        });
                }, 300);
            });

            // Navigation clavier (flèches + Entrée)
            let selectedIndex = -1;

            searchInput.addEventListener('keydown', function(e) {
                const items = resultsList.querySelectorAll('a');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                    highlightItem(items, selectedIndex);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    selectedIndex = Math.max(selectedIndex - 1, -1);
                    highlightItem(items, selectedIndex);
                } else if (e.key === 'Enter') {
                    if (selectedIndex >= 0 && items[selectedIndex]) {
                        e.preventDefault();
                        window.location.href = items[selectedIndex].href;
                    }
                    // Sinon, le formulaire est soumis normalement
                }
            });

            function highlightItem(items, index) {
                items.forEach((item, i) => {
                    if (i === index) {
                        item.classList.add('bg-ink-50');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('bg-ink-50');
                    }
                });
            }

            // Cacher les résultats en cas de clic à l'extérieur
            document.addEventListener('click', function(e) {
                const searchContainer = document.getElementById('search-form');
                if (searchContainer && !searchContainer.contains(e.target)) {
                    resultsContainer.classList.add('hidden');
                    selectedIndex = -1;
                }
            });

            // Fermer avec la touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    resultsContainer.classList.add('hidden');
                    selectedIndex = -1;
                }
            });

            // Réinitialiser l'index quand on ouvre/ferme les résultats
            const observer = new MutationObserver(() => {
                if (resultsContainer.classList.contains('hidden')) {
                    selectedIndex = -1;
                }
            });
            observer.observe(resultsContainer, { attributes: true, attributeFilter: ['class'] });
        });
    </script>

    @stack('scripts')
</body>
</html>