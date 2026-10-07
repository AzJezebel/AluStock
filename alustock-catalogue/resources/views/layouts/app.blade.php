{{-- resources/views/layouts/app.blade.php
     ============================================================
     DIRECTION "BLUEPRINT" — plan technique / papier millimétré
     Complètement différente de la direction "rouille industrielle" :
     fond sombre, traits fins plutôt que blocs pleins, typo mono
     omniprésente façon CAO, pas d'ombres ni de coins arrondis.
     ============================================================ --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AluStock - Catalogue de référence aluminium industriel')</title>

    <!-- Fonts : Space Grotesk (titres/chiffres) + JetBrains Mono (tout le reste) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles (Tailwind via CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Les couleurs pointent vers des variables CSS (voir <style> juste en dessous)
        // plutôt que des valeurs en dur : ça permet de changer toute la palette du
        // site instantanément, sans recharger la page — utile pour le switcher de
        // test sur la home, et plus propre pour figer un choix définitif plus tard.
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['JetBrains Mono', 'monospace'],
                        display: ['Space Grotesk', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        ink: {
                            50:  'var(--ink-50)',
                            100: 'var(--ink-100)',
                            200: 'var(--ink-200)',
                            300: 'var(--ink-300)',
                            400: 'var(--ink-400)',
                            500: 'var(--ink-500)',
                            600: 'var(--ink-600)',
                            700: 'var(--ink-700)',
                            800: 'var(--ink-800)',
                            900: 'var(--ink-900)',
                            950: 'var(--ink-950)',
                        },
                        amber: {
                            50:  'var(--amber-50)',
                            100: 'var(--amber-100)',
                            200: 'var(--amber-200)',
                            300: 'var(--amber-300)',
                            400: 'var(--amber-400)',
                            500: 'var(--amber-500)',
                            600: 'var(--amber-600)',
                            700: 'var(--amber-700)',
                            800: 'var(--amber-800)',
                            900: 'var(--amber-900)',
                            950: 'var(--amber-950)',
                        },
                        // "fg" = couleur de texte forte (titres, valeurs en emphase).
                        // Blanc sur les palettes sombres, presque noir sur les palettes
                        // claires — remplace les anciens "text-white" en dur, qui
                        // cassaient sur fond clair.
                        fg: 'var(--fg)',
                    },
                }
            }
        }
    </script>

    {{-- ============================================================
         PALETTES — 4 jeux de variables CSS.
         :root = palette active par défaut ("Cyan blueprint").
         Les 3 blocs html[data-theme="..."] ne s'appliquent que si on pose
         cet attribut sur <html> — ce que fait le switcher de test sur la home.
         Pour figer un choix définitivement : remplace directement les valeurs
         du bloc :root par celles de la palette voulue (et tu peux retirer
         le switcher + les blocs data-theme).
         ============================================================ --}}
    <style>
        :root {
            /* Cyan blueprint (par défaut) */
            --ink-50:  #EAF0F3; --ink-100: #CBD8DE; --ink-200: #9FB4BF; --ink-300: #71909D;
            --ink-400: #506E7A; --ink-500: #3A535E; --ink-600: #283C45; --ink-700: #1B2930;
            --ink-800: #10191F; --ink-900: #0A1119; --ink-950: #050A0F;

            --amber-50:  #ECFDFB; --amber-100: #CFFBF3; --amber-200: #A4F4E6; --amber-300: #6FE8D4;
            --amber-400: #3FD3BE; --amber-500: #22B6A3; --amber-600: #178F83; --amber-700: #14746B;
            --amber-800: #135D58; --amber-900: #124D49; --amber-950: #052E2C;

            /* Texte fort (titres) : blanc par défaut. Les palettes claires le
               redéfinissent en une teinte sombre — voir plus bas. */
            --fg: #FFFFFF;

            /* Filtre appliqué aux schémas techniques (scans noir/blanc) : sur fond
               sombre on les inverse pour éviter une plaque blanche éblouissante ;
               les palettes claires désactivent ce filtre (le scan est déjà adapté
               à un fond clair). Survol = couleurs d'origine. */
            --schema-filter: invert(0.93) brightness(0.92) contrast(1.05);
            --schema-filter-hover: invert(0) brightness(1) contrast(1);
        }

        /* Ambre sécurité — plan annoté au marqueur orange */
        html[data-theme="ambre"] {
            --ink-50:  #F3EFEA; --ink-100: #DCD3C8; --ink-200: #B8A897; --ink-300: #93806C;
            --ink-400: #6E5D4C; --ink-500: #564737; --ink-600: #413526; --ink-700: #30251A;
            --ink-800: #211811; --ink-900: #160F0A; --ink-950: #0C0805;

            --amber-50:  #FEF6EA; --amber-100: #FCE5C2; --amber-200: #F9CC8A; --amber-300: #F3AC4C;
            --amber-400: #E8912B; --amber-500: #CC7818; --amber-600: #A35F13; --amber-700: #7E4A11;
            --amber-800: #5F3810; --amber-900: #4C2D0F; --amber-950: #281705;
        }

        /* Vert phosphore — terminal / oscilloscope */
        html[data-theme="vert"] {
            --ink-50:  #E9F1EA; --ink-100: #C6DBC9; --ink-200: #96B99B; --ink-300: #679470;
            --ink-400: #476A4E; --ink-500: #334D38; --ink-600: #243627; --ink-700: #18241A;
            --ink-800: #0F1710; --ink-900: #090F0A; --ink-950: #040805;

            --amber-50:  #EAFFF1; --amber-100: #C5FFD8; --amber-200: #93FFB4; --amber-300: #5CFA8C;
            --amber-400: #39E86C; --amber-500: #22C455; --amber-600: #189A42; --amber-700: #157A36;
            --amber-800: #14602D; --amber-900: #124E27; --amber-950: #062B13;
        }

        /* Blanc sur noir — monochrome, aucune teinte */
        html[data-theme="mono"] {
            --ink-50:  #F2F2F2; --ink-100: #DCDCDC; --ink-200: #B8B8B8; --ink-300: #8F8F8F;
            --ink-400: #6B6B6B; --ink-500: #4D4D4D; --ink-600: #363636; --ink-700: #262626;
            --ink-800: #181818; --ink-900: #0E0E0E; --ink-950: #070707;

            --amber-50:  #FFFFFF; --amber-100: #F5F5F5; --amber-200: #E8E8E8; --amber-300: #D6D6D6;
            --amber-400: #C2C2C2; --amber-500: #ADADAD; --amber-600: #8F8F8F; --amber-700: #737373;
            --amber-800: #5C5C5C; --amber-900: #4A4A4A; --amber-950: #2B2B2B;
        }

        /* Cyanotype classique — le "blueprint" au sens historique : traits clairs
           sur bleu de Prusse, façon tirage de plan d'architecte à l'ancienne. */
        html[data-theme="cyanotype"] {
            --ink-50:  #EDF2FA; --ink-100: #D3DEEF; --ink-200: #A9BEDD; --ink-300: #7E9DC9;
            --ink-400: #5A7FB0; --ink-500: #405F87; --ink-600: #2E4763; --ink-700: #213349;
            --ink-800: #162334; --ink-900: #0D1520; --ink-950: #070C14;

            --amber-50:  #FFFFFF; --amber-100: #F5F8FF; --amber-200: #E3EBFF; --amber-300: #C7D8FF;
            --amber-400: #A9C2FF; --amber-500: #8AA8F5; --amber-600: #6E8CDD; --amber-700: #5672BD;
            --amber-800: #435897; --amber-900: #35457A; --amber-950: #1E2847;
        }

        /* Rouge alerte — marqueur rouge sur graphite chaud */
        html[data-theme="rouge"] {
            --ink-50:  #F5F1EF; --ink-100: #E2D8D3; --ink-200: #C4B3AA; --ink-300: #A58C80;
            --ink-400: #856A5E; --ink-500: #664F46; --ink-600: #4D3A33; --ink-700: #382925;
            --ink-800: #271B18; --ink-900: #19100E; --ink-950: #0D0807;

            --amber-50:  #FFF1EE; --amber-100: #FFDCD4; --amber-200: #FFB7A8; --amber-300: #FF8F77;
            --amber-400: #FF6647; --amber-500: #E8432A; --amber-600: #C22E1A; --amber-700: #9C2315;
            --amber-800: #771A10; --amber-900: #5E150D; --amber-950: #330A06;
        }

        /* Violet UV — synthé/labo, accent magenta sur violet profond */
        html[data-theme="violet"] {
            --ink-50:  #F1EEF6; --ink-100: #DAD1E8; --ink-200: #B6A5D2; --ink-300: #8F79B9;
            --ink-400: #6C579D; --ink-500: #513F79; --ink-600: #3C2F5C; --ink-700: #2B2244;
            --ink-800: #1C162E; --ink-900: #120D1E; --ink-950: #090711;

            --amber-50:  #FDEEFF; --amber-100: #F8D2FF; --amber-200: #F1A8FF; --amber-300: #E677FF;
            --amber-400: #D848FF; --amber-500: #C520F0; --amber-600: #9E14C2; --amber-700: #7B109A;
            --amber-800: #5E0C76; --amber-900: #4A0A5D; --amber-950: #280533;
        }

        /* Graphite doux — même ossature neutre que la palette active, mais le
           ton le plus clair (ink-50) est un gris chaud mat plutôt qu'un blanc
           froid : moins de glare partout où une plaque claire reste utilisée
           (photos de catégories, états vides). Accent bronze discret. */
        html[data-theme="graphite"] {
            --ink-50:  #D9D6D0; --ink-100: #C3C0B9; --ink-200: #A29E96; --ink-300: #827E75;
            --ink-400: #635F57; --ink-500: #4A463F; --ink-600: #38352F; --ink-700: #282621;
            --ink-800: #1B1916; --ink-900: #110F0D; --ink-950: #0A0908;

            --amber-50:  #F2E9D8; --amber-100: #E6D3B0; --amber-200: #D3B378; --amber-300: #BF9450;
            --amber-400: #A87B3A; --amber-500: #8C6429; --amber-600: #6E4E1F; --amber-700: #543C18;
            --amber-800: #3E2C12; --amber-900: #2E210D; --amber-950: #181206;
        }

        /* Sable industriel — même idée (ink-50 mat, pas blanc) avec un accent
           orange brûlé plus chaud/saturé que le graphite. */
        html[data-theme="sable"] {
            --ink-50:  #E8E2D6; --ink-100: #D6CDB9; --ink-200: #B9AD8E; --ink-300: #9C8E68;
            --ink-400: #7C6F50; --ink-500: #5F543C; --ink-600: #48402D; --ink-700: #332D1F;
            --ink-800: #221E15; --ink-900: #15120D; --ink-950: #0B0907;

            --amber-50:  #FFE9D6; --amber-100: #FFCBA3; --amber-200: #FFA968; --amber-300: #FF8A3D;
            --amber-400: #F06E22; --amber-500: #CC561A; --amber-600: #A34414; --amber-700: #7E350F;
            --amber-800: #5F290C; --amber-900: #4C2109; --amber-950: #281005;
        }

        /* ============================================================
           PALETTES CLAIRES — même échelle ink/amber, mais SENS INVERSÉ :
           ink-50 = texte le plus sombre, ink-950 = fond le plus clair
           (au lieu de l'inverse dans les palettes sombres ci-dessus).
           Comme tous les composants utilisent systématiquement "bg-ink-9xx
           + text-ink-1xx/50" pour le contraste fort et "bg-ink-9xx + text-
           ink-4xx/500" pour le texte discret, inverser juste les valeurs de
           l'échelle suffit à retourner tout le site en clair sans toucher
           au balisage. --fg et --schema-filter sont redéfinis en plus (texte
           fort en dur et inversion des schémas ne doivent s'appliquer
           qu'en sombre).
           ============================================================ --}}

        /* Papier ingénieur — carnet millimétré classique, accent rouge crayon */
        html[data-theme="papier"] {
            --ink-50:  #14181B; --ink-100: #262B2F; --ink-200: #3D444A; --ink-300: #565F66;
            --ink-400: #707980; --ink-500: #8B939A; --ink-600: #A8AFB4; --ink-700: #C4C9CC;
            --ink-800: #DDE0E2; --ink-900: #EFF0F1; --ink-950: #FAFAF9;

            --amber-50:  #FDECEC; --amber-100: #FAD0D0; --amber-200: #F2A3A3; --amber-300: #E57373;
            --amber-400: #C23B3B; --amber-500: #A62A2A; --amber-600: #8C2222; --amber-700: #701A1A;
            --amber-800: #571414; --amber-900: #430F0F; --amber-950: #2B0A0A;

            --fg: #14181B;
            --schema-filter: none;
            --schema-filter-hover: none;
        }

        /* Aluminium brossé — gris argenté froid, accent bleu acier */
        html[data-theme="aluminium"] {
            --ink-50:  #12161A; --ink-100: #232B31; --ink-200: #3A454C; --ink-300: #52606A;
            --ink-400: #6C7A83; --ink-500: #88949C; --ink-600: #A6AFB5; --ink-700: #C2C9CD;
            --ink-800: #DADEE1; --ink-900: #ECEEEF; --ink-950: #F6F7F7;

            --amber-50:  #EAF2FB; --amber-100: #CFE2F5; --amber-200: #9CC2EA; --amber-300: #649EDC;
            --amber-400: #3379C9; --amber-500: #255FA8; --amber-600: #1C4C8A; --amber-700: #163C6D;
            --amber-800: #112E54; --amber-900: #0D2341; --amber-950: #081627;

            --fg: #12161A;
            --schema-filter: none;
            --schema-filter-hover: none;
        }

        /* Blanc atelier — blanc pur, noir net, accent orange sécurité */
        html[data-theme="atelier"] {
            --ink-50:  #101010; --ink-100: #232323; --ink-200: #3D3D3D; --ink-300: #585858;
            --ink-400: #747474; --ink-500: #919191; --ink-600: #AEAEAE; --ink-700: #C9C9C9;
            --ink-800: #E0E0E0; --ink-900: #F1F1F1; --ink-950: #FFFFFF;

            --amber-50:  #FFF2E8; --amber-100: #FFDCC0; --amber-200: #FFB980; --amber-300: #FF9A4D;
            --amber-400: #E87A1F; --amber-500: #C4620F; --amber-600: #A34F0C; --amber-700: #7E3D0A;
            --amber-800: #602F08; --amber-900: #4C2507; --amber-950: #281303;

            --fg: #101010;
            --schema-filter: none;
            --schema-filter-hover: none;
        }
    </style>

    <script>
        // Applique un choix de palette sauvegardé (si le switcher de test a été utilisé)
        // avant le premier rendu, pour éviter un flash de la palette par défaut.
        (function () {
            var saved = localStorage.getItem('alustock-theme-test');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>

    {{-- Style global : structure, grille millimétrée, sticky footer
         type="text/tailwindcss" est nécessaire pour que theme() soit résolu par le
         CDN Tailwind — un <style> classique ne comprend pas cette fonction et
         "casse" silencieusement les règles qui l'utilisent (c'est ce qui rendait
         les repères d'angle et la scrollbar invisibles). --}}
    <style type="text/tailwindcss">
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

        .main-content {
            flex: 1 0 auto;
        }

        .main-footer {
            flex-shrink: 0;
        }

        .sidebar-sticky {
            position: sticky;
            top: 80px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }

        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        @media (max-width: 768px) {
            .sidebar-sticky { position: relative; top: 0; max-height: none; }
        }

        /* Papier millimétré : grille de fond très discrète */
        .blueprint-grid {
            background-image:
                linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* Curseur clignotant façon terminal, utilisé dans la barre de recherche */
        @keyframes blink { 0%, 49% { opacity: 1; } 50%, 100% { opacity: 0; } }
        .caret { animation: blink 1s step-end infinite; }

        /* Repères d'angle façon cartouche de dessin technique */
        .tick { position: absolute; width: 9px; height: 9px; border-color: theme('colors.ink.600'); }
        .tick-tl { top: -1px; left: -1px; border-top: 1px solid; border-left: 1px solid; }
        .tick-br { bottom: -1px; right: -1px; border-bottom: 1px solid; border-right: 1px solid; }

        .card-blueprint:hover .tick { border-color: theme('colors.amber.500'); }

        /* Schémas techniques — voir --schema-filter dans :root */
        .schema-img { filter: var(--schema-filter); transition: filter .3s ease, transform .3s ease; }
        .group:hover .schema-img { filter: var(--schema-filter-hover); }

        #search-results::-webkit-scrollbar { width: 4px; }
        #search-results::-webkit-scrollbar-track { background: theme('colors.ink.800'); }
        #search-results::-webkit-scrollbar-thumb { background: theme('colors.ink.500'); }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-ink-950 text-ink-50">

    {{-- ============================================================
         BANDEAU UTILITAIRE — façon cartouche de plan
         ============================================================ --}}
    <div class="bg-ink-900 border-b border-ink-700 text-ink-400 text-[11px] tracking-wide flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5 flex items-center justify-between">
            <span class="hidden sm:inline truncate">CATALOGUE DE RÉFÉRENCE — ALUMINIUM INDUSTRIEL</span>
            <div class="flex items-center gap-5 ml-auto">
                <a href="#" class="hover:text-amber-400 transition">DOCUMENTATION</a>
                <a href="{{ route('contact.show') }}" class="hover:text-amber-400 transition {{ request()->routeIs('contact.*') ? 'text-amber-400' : '' }}">CONTACT</a>
            </div>
        </div>
    </div>

    {{-- ============================================================
         EN-TÊTE
         ============================================================ --}}
    <header class="bg-ink-900 border-b border-ink-700 flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row md:items-center gap-4">
            @include('public.partials.logo')

            {{-- Barre de recherche façon terminal --}}
            <form action="{{ route('search.index') }}" method="GET" class="relative flex-1 max-w-2xl mx-auto w-full" id="search-form">
                <label for="search-input" class="sr-only">Rechercher un composant</label>
                <div class="flex items-center gap-2 border border-ink-600 h-[42px] px-3.5 focus-within:border-amber-500 transition">
                    <span class="text-amber-500 text-sm">&gt;</span>
                    <input type="text"
                           name="q"
                           id="search-input"
                           placeholder="REF. / ALLIAGE / DIMENSION"
                           class="flex-1 bg-transparent border-none outline-none text-[12.5px] tracking-wide text-ink-50 placeholder-ink-400"
                           autocomplete="off">
                    <span class="caret w-[7px] h-[14px] bg-amber-500"></span>
                </div>

                {{-- Résultats autocomplétion --}}
                <div id="search-results" class="absolute left-0 right-0 top-full mt-1 bg-ink-900 border border-ink-600 overflow-hidden hidden z-50">
                    <div id="search-results-list" class="divide-y divide-ink-700 max-h-80 overflow-y-auto"></div>
                    <div class="px-4 py-2 bg-ink-800 text-[11px] text-ink-400 text-center border-t border-ink-700">
                        ENTRÉE POUR VOIR TOUS LES RÉSULTATS
                    </div>
                </div>
            </form>

            <div class="font-mono text-[11px] text-ink-400 tracking-wide whitespace-nowrap">
                N={{ number_format(
                    \Illuminate\Support\Facades\Cache::remember('layout_references_count_v2', 3600, fn () => \App\Models\Composant::count() + \App\Models\Ouvrage::count()),
                    0, '', ' '
                ) }} REFS
            </div>
        </div>
    </header>

    {{-- ============================================================
         CONTENU PRINCIPAL AVEC SIDEBAR
         ============================================================ --}}
    <div class="main-content blueprint-grid flex flex-col md:flex-row max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6 w-full">

        {{-- SIDEBAR GAUCHE --}}
        <aside class="md:w-64 lg:w-72 flex-shrink-0">
            <div class="sidebar-sticky bg-ink-900 border border-ink-700">

                <button class="md:hidden w-full flex items-center justify-between text-left text-sm font-medium text-ink-200 p-3 hover:bg-ink-800"
                        onclick="document.getElementById('sidebar-menu').classList.toggle('hidden')">
                    <span>MENU</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <nav id="sidebar-menu" class="hidden md:block text-sm">
                    @include('public.partials.sidebar-menu')
                </nav>
            </div>
        </aside>

        {{-- CONTENU PRINCIPAL --}}
        <main class="flex-1 min-w-0">
            @hasSection('breadcrumb')
                <div class="mb-4 text-xs text-ink-400 tracking-wide">
                    @yield('breadcrumb')
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-4 bg-ink-900 border-l-2 border-amber-500 text-ink-100 text-sm" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-ink-900 border-l-2 border-red-500 text-red-300 text-sm" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 p-4 bg-ink-900 border-l-2 border-amber-400 text-ink-100 text-sm" role="alert">
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    {{-- ============================================================
         PIED DE PAGE
         ============================================================ --}}
    <footer class="main-footer bg-ink-900 border-t border-ink-700 text-ink-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <span class="font-display text-lg font-bold text-ink-50">Alu<span class="text-amber-500">Stock</span></span>
                    <p class="text-sm text-ink-400 mt-2">Distribution industrielle d'aluminium et profilés structuraux depuis 2024.</p>
                </div>
                <div>
                    <h4 class="font-mono text-ink-200 font-semibold text-[11px] uppercase tracking-widest">Navigation</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        @foreach(($navCategories ?? []) as $navCategory)
                            <li><a href="{{ route('ouvrages.index', ['categorie' => $navCategory->slug]) }}" class="hover:text-amber-400 transition">{{ $navCategory->nom }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-mono text-ink-200 font-semibold text-[11px] uppercase tracking-widest">Légal</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li><a href="#" class="hover:text-amber-400 transition">Mentions légales</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition">Confidentialité</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-mono text-ink-200 font-semibold text-[11px] uppercase tracking-widest">Contact</h4>
                    <ul class="mt-2 space-y-1 text-sm">
                        <li class="text-ink-400">contact@alustock.fr</li>
                        <li class="text-ink-400">+33 (0)1 23 45 67 89</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-ink-700 mt-6 pt-4 text-center text-[11px] text-ink-500 tracking-wide">
                &copy; {{ date('Y') }} AluStock. Tous droits réservés. Catalogue de référence — aluminium industriel et profilés structuraux.
            </div>
        </div>
    </footer>

    {{-- ============================================================
         SCRIPTS (autocomplétion, inchangée dans sa logique)
         ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const resultsContainer = document.getElementById('search-results');
            const resultsList = document.getElementById('search-results-list');
            let searchTimeout = null;

            if (!searchInput || !resultsContainer || !resultsList) return;

            function renderResults(data) {
                if (data.length === 0) {
                    resultsList.innerHTML = `
                        <div class="px-4 py-4 text-sm text-ink-400 text-center">
                            AUCUN RÉSULTAT POUR "<span class="text-ink-100">${searchInput.value.trim()}</span>"
                        </div>
                    `;
                    resultsContainer.classList.remove('hidden');
                    return;
                }

                let html = '';
                data.forEach(item => {
                    const icon = item.type === 'ouvrage'
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>';

                    html += `
                        <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-ink-800 transition group">
                            <div class="w-8 h-8 flex-shrink-0 border border-ink-600 flex items-center justify-center text-ink-400 group-hover:border-amber-500 group-hover:text-amber-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-ink-100 group-hover:text-amber-400 transition truncate">
                                    ${item.label}
                                </div>
                                <span class="text-xs text-ink-500 font-mono">RÉF. ${item.reference}</span>
                            </div>
                            <svg class="w-4 h-4 text-ink-500 group-hover:text-amber-500 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    `;
                });

                resultsList.innerHTML = html;
                resultsContainer.classList.remove('hidden');
            }

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);
                if (query.length < 2) { resultsContainer.classList.add('hidden'); return; }

                searchTimeout = setTimeout(function() {
                    fetch(`/search/autocomplete?q=${encodeURIComponent(query)}`)
                        .then(response => { if (!response.ok) throw new Error('Erreur réseau'); return response.json(); })
                        .then(renderResults)
                        .catch(() => {
                            resultsList.innerHTML = `<div class="px-4 py-4 text-sm text-red-400 text-center">ERREUR RÉSEAU</div>`;
                            resultsContainer.classList.remove('hidden');
                        });
                }, 300);
            });

            document.addEventListener('click', function(e) {
                const searchContainer = document.getElementById('search-form');
                if (searchContainer && !searchContainer.contains(e.target)) {
                    resultsContainer.classList.add('hidden');
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') resultsContainer.classList.add('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>