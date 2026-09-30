{{-- resources/views/public/home.blade.php
     Direction "Blueprint" — hero "cartouche", catégories en vedette,
     puis deux grandes cartes d'entrée vers les index complets.
     Section "Gammes" retirée. --}}
@extends('layouts.app')

@section('title', 'AluStock — Catalogue de référence aluminium industriel')

@section('content')
<div>

    {{-- ============================================================
         SWITCHER DE PALETTE — TEMPORAIRE, POUR TESTER LES 7 DIRECTIONS
         À retirer une fois un choix figé (voir commentaire dans app.blade.php
         pour comment rendre ce choix permanent).
         ============================================================ --}}
    <div class="mb-6 flex flex-wrap items-center gap-2 p-2.5 bg-ink-900 border border-dashed border-amber-500/40">
        <span class="font-mono text-[10px] uppercase tracking-widest text-amber-400 mr-1">
            Test palette (temporaire) :
        </span>
        <button type="button" onclick="setTestTheme(null)"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Cyan blueprint
        </button>
        <button type="button" onclick="setTestTheme('ambre')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Ambre sécurité
        </button>
        <button type="button" onclick="setTestTheme('vert')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Vert phosphore
        </button>
        <button type="button" onclick="setTestTheme('mono')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Blanc sur noir
        </button>
        <button type="button" onclick="setTestTheme('cyanotype')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Cyanotype classique
        </button>
        <button type="button" onclick="setTestTheme('rouge')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Rouge alerte
        </button>
        <button type="button" onclick="setTestTheme('violet')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Violet UV
        </button>
        <button type="button" onclick="setTestTheme('graphite')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Graphite doux
        </button>
        <button type="button" onclick="setTestTheme('sable')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            Sable industriel
        </button>
        <span class="w-px self-stretch bg-ink-700 mx-1"></span>
        <button type="button" onclick="setTestTheme('papier')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            ☀ Papier ingénieur
        </button>
        <button type="button" onclick="setTestTheme('aluminium')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            ☀ Aluminium brossé
        </button>
        <button type="button" onclick="setTestTheme('atelier')"
                class="palette-btn px-2.5 py-1 font-mono text-[10.5px] border border-ink-700 text-ink-300 hover:border-amber-500 hover:text-fg transition">
            ☀ Blanc atelier
        </button>
    </div>
    <script>
        function setTestTheme(theme) {
            if (theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('alustock-theme-test', theme);
            } else {
                document.documentElement.removeAttribute('data-theme');
                localStorage.removeItem('alustock-theme-test');
            }
        }
    </script>

    {{-- ============================================================
         HERO — plaque "cartouche" façon plan technique
         ============================================================ --}}
    <div class="relative bg-ink-900 border border-ink-700 mb-10 overflow-hidden">
        <div class="absolute inset-0 blueprint-grid pointer-events-none"></div>

        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>

        <div class="relative grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 sm:p-10">

            <div class="lg:col-span-2">
                <span class="font-mono text-[11px] font-semibold uppercase tracking-[0.2em] text-amber-400">
                    Catalogue de référence industriel
                </span>
                <h1 class="mt-3 font-display text-3xl sm:text-4xl lg:text-5xl font-bold text-fg tracking-tight leading-tight">
                    ALU<span class="text-amber-400">STOCK</span>
                </h1>
                <p class="mt-4 font-mono text-[13px] text-ink-300 max-w-xl leading-relaxed">
                    Plus de {{ number_format($stats['references'], 0, ',', ' ') }} références documentées — profilés T-slot, tôles, visserie,
                    connecteurs et extrusions sur mesure. Fiches techniques EN disponibles pour chaque produit.
                </p>

                <form action="{{ route('search.index') }}" method="GET" class="mt-6 flex max-w-xl">
                    <label for="hero-search" class="sr-only">Rechercher</label>
                    <div class="flex items-center gap-2 flex-1 border border-ink-600 bg-ink-950/60 h-11 px-3.5 focus-within:border-amber-500 transition">
                        <span class="text-amber-500 text-sm">&gt;</span>
                        <input type="text" id="hero-search" name="q"
                               placeholder="Référence, alliage, dimension..."
                               class="flex-1 bg-transparent border-none outline-none text-[12.5px] text-fg placeholder-ink-400"
                               autocomplete="off">
                    </div>
                    <button type="submit"
                            class="px-5 h-11 bg-amber-600 hover:bg-amber-500 text-ink-950 text-[12.5px] font-bold uppercase tracking-wider transition">
                        Rechercher
                    </button>
                </form>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 gap-3 h-full">
                <div class="border border-ink-700 bg-ink-950/50 p-5 flex-1 flex flex-col items-center justify-center text-center">
                    <span class="block font-display text-4xl sm:text-5xl font-bold text-fg">{{ number_format($stats['references'], 0, ',', ' ') }}</span>
                    <span class="font-mono text-ink-400 text-[10px] uppercase tracking-widest mt-1">Références</span>
                </div>
                <div class="border border-ink-700 bg-ink-950/50 p-5 flex-1 flex flex-col items-center justify-center text-center">
                    <span class="block font-display text-4xl sm:text-5xl font-bold text-fg">{{ $stats['categories'] }}</span>
                    <span class="font-mono text-ink-400 text-[10px] uppercase tracking-widest mt-1">Catégories</span>
                </div>
            </div>

        </div>
    </div>

    {{-- ============================================================
         CATÉGORIES EN VEDETTE
         ============================================================ --}}
    <div class="flex items-end justify-between mb-4">
        <h2 class="font-mono text-[11px] font-semibold uppercase tracking-widest text-ink-500">
            Parcourir par catégorie
        </h2>
        <a href="{{ route('categories.index') }}"
           class="inline-flex items-center gap-1 font-mono text-[11px] text-amber-400 hover:text-amber-600 transition">
            Voir toutes les catégories
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        @forelse($featuredCategories as $category)
            <a href="{{ route('ouvrages.index', ['categorie' => $category->slug]) }}"
               class="card-blueprint group relative bg-ink-900 border border-ink-800 hover:border-amber-500/60 transition-colors flex flex-col">

                <span class="tick tick-tl" aria-hidden="true"></span>
                <span class="tick tick-br" aria-hidden="true"></span>

                <div class="h-20 bg-ink-950 border-b border-ink-800 flex items-center justify-center">
                    <span class="text-2xl">{{ $category->icone ?? '▦' }}</span>
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="text-sm font-semibold text-ink-100 group-hover:text-amber-600 transition">
                            {{ $category->nom }}
                        </h4>
                        <span class="shrink-0 font-mono text-[10px] px-2 py-0.5 border border-amber-500/30 bg-amber-500/10 text-amber-400">
                            {{ number_format($category->ouvrages_count ?? 0, 0, ',', ' ') }}
                        </span>
                    </div>
                    @if($category->description)
                        <p class="text-xs text-ink-400 mt-1.5 line-clamp-2">
                            {{ Str::limit($category->description, 60) }}
                        </p>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-8 bg-ink-900 border border-ink-800 text-ink-500">
                <p class="text-sm">Aucune catégorie disponible.</p>
            </div>
        @endforelse
    </div>

    {{-- ============================================================
         ACCÈS DIRECTS — tout l'index d'un coup
         ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <a href="{{ route('ouvrages.index') }}"
           class="card-blueprint group relative bg-ink-900 border border-ink-700 hover:border-amber-500/60 transition-colors flex items-center gap-5 p-6">

            <span class="tick tick-tl" aria-hidden="true"></span>
            <span class="tick tick-br" aria-hidden="true"></span>

            <div class="w-14 h-14 shrink-0 border border-ink-700 bg-ink-950 flex items-center justify-center text-amber-400 group-hover:border-amber-500/60 transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <h3 class="font-display text-lg font-bold text-fg group-hover:text-amber-600 transition">
                    Tous les ouvrages
                </h3>
                <p class="font-mono text-[11px] text-ink-400 mt-0.5">
                    Fenêtres, portes, garde-corps — produits finis assemblés
                </p>
            </div>

            <svg class="w-5 h-5 text-ink-600 group-hover:text-amber-500 group-hover:translate-x-1 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('composants.index') }}"
           class="card-blueprint group relative bg-ink-900 border border-ink-700 hover:border-amber-500/60 transition-colors flex items-center gap-5 p-6">

            <span class="tick tick-tl" aria-hidden="true"></span>
            <span class="tick tick-br" aria-hidden="true"></span>

            <div class="w-14 h-14 shrink-0 border border-ink-700 bg-ink-950 flex items-center justify-center text-amber-400 group-hover:border-amber-500/60 transition-colors">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <h3 class="font-display text-lg font-bold text-fg group-hover:text-amber-600 transition">
                    Tous les composants
                </h3>
                <p class="font-mono text-[11px] text-ink-400 mt-0.5">
                    Profilés, visserie, connecteurs — références atomiques
                </p>
            </div>

            <svg class="w-5 h-5 text-ink-600 group-hover:text-amber-500 group-hover:translate-x-1 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

    </div>

</div>
@endsection