{{-- resources/views/public/search/index.blade.php
     Direction "Blueprint" — cohérent avec le reste (panneaux sombres, mono, ticks). --}}
@extends('layouts.app')

@section('title', 'Recherche - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">Recherche</span>
    @if($query)
        <span class="mx-2 text-ink-600">›</span>
        <span class="text-ink-500">"{{ $query }}"</span>
    @endif
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-fg">Recherche</h1>
        @if($query)
            <p class="text-ink-400 text-sm mt-1">
                Résultats pour <span class="font-medium text-ink-200">"{{ $query }}"</span>
                <span class="text-ink-500 ml-2 font-mono">({{ $ouvrages->total() }} résultats)</span>
            </p>
        @else
            <p class="text-ink-400 text-sm mt-1">Affinez votre recherche avec les filtres ci-dessous.</p>
        @endif
    </div>

    {{-- Filtres --}}
    <div class="bg-ink-900 border border-ink-800 p-4 mb-6">
        <form action="{{ route('search.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            {{-- Mots-clés --}}
            <div class="flex-1">
                <label for="search-q" class="sr-only">Mots-clés</label>
                <input type="text"
                       id="search-q"
                       name="q"
                       value="{{ $query }}"
                       placeholder="Rechercher par référence, nom, matière..."
                       class="w-full px-4 py-2.5 bg-ink-950 border border-ink-700 text-sm text-fg placeholder-ink-500 focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>

            {{-- Filtre : Catégorie --}}
            <div class="md:w-48">
                <label for="search-categorie" class="sr-only">Catégorie</label>
                <select id="search-categorie"
                        name="categorie"
                        class="w-full px-4 py-2.5 bg-ink-950 border border-ink-700 text-sm text-fg focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500 transition">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ $categorie == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Bouton --}}
            <button type="submit"
                    class="px-6 py-2.5 bg-amber-600 hover:bg-amber-500 text-ink-950 text-sm font-bold uppercase tracking-wider transition">
                Filtrer
            </button>

            @if($query || $categorie)
                <a href="{{ route('search.index') }}"
                   class="inline-flex items-center px-4 py-2.5 text-sm text-ink-400 hover:text-fg transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Effacer
                </a>
            @endif
        </form>
    </div>

    {{-- Résultats --}}
    @if($ouvrages->count() > 0 || $composants->count() > 0)

        {{-- Onglets type de résultat --}}
        <div class="flex border-b border-ink-800 mb-6 font-mono text-[12px]">
            <a href="{{ route('search.index', array_merge(request()->query(), ['type' => 'all'])) }}"
               class="px-4 py-2 font-medium border-b-2 transition {{ $type === 'all' ? 'border-amber-500 text-amber-400' : 'border-transparent text-ink-500 hover:text-fg' }}">
                Tous ({{ $ouvrages->total() + $composants->count() }})
            </a>
            <a href="{{ route('search.index', array_merge(request()->query(), ['type' => 'ouvrages'])) }}"
               class="px-4 py-2 font-medium border-b-2 transition {{ $type === 'ouvrages' ? 'border-amber-500 text-amber-400' : 'border-transparent text-ink-500 hover:text-fg' }}">
                Ouvrages ({{ $ouvrages->total() }})
            </a>
            <a href="{{ route('search.index', array_merge(request()->query(), ['type' => 'composants'])) }}"
               class="px-4 py-2 font-medium border-b-2 transition {{ $type === 'composants' ? 'border-amber-500 text-amber-400' : 'border-transparent text-ink-500 hover:text-fg' }}">
                Composants ({{ $composants->count() }})
            </a>
        </div>

        {{-- Liste des ouvrages --}}
        @if($type === 'all' || $type === 'ouvrages')
            @if($ouvrages->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach($ouvrages as $ouvrage)
                        @include('public.partials.ouvrage-card', ['ouvrage' => $ouvrage])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $ouvrages->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-8 text-ink-500">
                    <p>Aucun ouvrage trouvé.</p>
                </div>
            @endif
        @endif

        {{-- Liste des composants --}}
        @if(($type === 'all' || $type === 'composants') && $composants->count() > 0)
            <div class="mt-6">
                <h3 class="font-mono text-[10px] font-semibold text-ink-500 uppercase tracking-widest mb-3">Composants trouvés</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                    @foreach($composants as $composant)
                        @include('public.partials.composant-card', ['composant' => $composant])
                    @endforeach
                </div>
            </div>
        @endif

    @elseif($query || $categorie)
        {{-- Aucun résultat --}}
        <div class="text-center py-16 bg-ink-900 border border-ink-800">
            <div class="text-ink-500">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-lg font-medium text-ink-300">Aucun résultat trouvé</p>
                <p class="text-sm mt-1">Essayez de modifier vos critères de recherche.</p>
            </div>
        </div>
    @else
        {{-- Message initial --}}
        <div class="text-center py-16 bg-ink-900 border border-ink-800">
            <div class="text-ink-500">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-lg font-medium text-ink-300">Que recherchez-vous ?</p>
                <p class="text-sm mt-1">Utilisez la barre de recherche ou les filtres ci-dessus.</p>
            </div>
        </div>
    @endif
</div>
@endsection