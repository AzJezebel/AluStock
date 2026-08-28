{{-- resources/views/public/search/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Recherche - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-2">›</span>
    <span class="text-ink-700 font-medium">Recherche</span>
    @if($query)
        <span class="mx-2">›</span>
        <span class="text-ink-500">"{{ $query }}"</span>
    @endif
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-ink-900">Recherche</h1>
        @if($query)
            <p class="text-ink-500 text-sm mt-1">
                Résultats pour <span class="font-medium text-ink-700">"{{ $query }}"</span>
                <span class="text-ink-400 ml-2">({{ $ouvrages->total() }} résultats)</span>
            </p>
        @else
            <p class="text-ink-500 text-sm mt-1">Affinez votre recherche avec les filtres ci-dessous.</p>
        @endif
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-4 mb-6">
        <form action="{{ route('search.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            {{-- Mots-clés --}}
            <div class="flex-1">
                <input type="text"
                       name="q"
                       value="{{ $query }}"
                       placeholder="Rechercher par référence, nom, matière..."
                       class="w-full px-4 py-2.5 bg-ink-50 border border-ink-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-transparent transition">
            </div>

            {{-- Filtre : Catégorie --}}
            <div class="md:w-48">
                <select name="categorie" 
                        class="w-full px-4 py-2.5 bg-ink-50 border border-ink-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-transparent transition">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ $categorie == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre : Gamme --}}
            <div class="md:w-48">
                <select name="gamme" 
                        class="w-full px-4 py-2.5 bg-ink-50 border border-ink-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-600 focus:border-transparent transition">
                    <option value="">Toutes les gammes</option>
                    @foreach($gammes as $g)
                        <option value="{{ $g->slug }}" {{ $gamme == $g->slug ? 'selected' : '' }}>
                            {{ $g->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Bouton --}}
            <button type="submit" 
                    class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition shadow-sm shadow-amber-600/25">
                Filtrer
            </button>

            @if($query || $categorie || $gamme)
                <a href="{{ route('search.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 text-sm text-ink-500 hover:text-ink-700 transition">
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
        <div class="flex border-b border-ink-200 mb-6">
            <a href="{{ route('search.index', array_merge(request()->query(), ['type' => 'all'])) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 transition {{ $type === 'all' ? 'border-amber-600 text-amber-700' : 'border-transparent text-ink-500 hover:text-ink-700' }}">
                Tous ({{ $ouvrages->total() + $composants->count() }})
            </a>
            <a href="{{ route('search.index', array_merge(request()->query(), ['type' => 'ouvrages'])) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 transition {{ $type === 'ouvrages' ? 'border-amber-600 text-amber-700' : 'border-transparent text-ink-500 hover:text-ink-700' }}">
                Ouvrages ({{ $ouvrages->total() }})
            </a>
            <a href="{{ route('search.index', array_merge(request()->query(), ['type' => 'composants'])) }}" 
               class="px-4 py-2 text-sm font-medium border-b-2 transition {{ $type === 'composants' ? 'border-amber-600 text-amber-700' : 'border-transparent text-ink-500 hover:text-ink-700' }}">
                Composants ({{ $composants->count() }})
            </a>
        </div>

        {{-- Liste des ouvrages --}}
        @if($type === 'all' || $type === 'ouvrages')
            @if($ouvrages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @foreach($ouvrages as $ouvrage)
                        <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                           class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300 flex items-start p-4 gap-4">
                            
                            {{-- Image --}}
                            <div class="w-16 h-16 bg-ink-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($ouvrage->image_principale)
                                    <img src="{{ asset('storage/' . $ouvrage->image_principale) }}" 
                                         alt="{{ $ouvrage->nom }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-ink-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Infos --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-ink-900 group-hover:text-amber-700 transition">
                                    {{ $ouvrage->nom }}
                                </h4>
                                <p class="text-xs text-ink-400 mt-0.5">Réf. {{ $ouvrage->reference }}</p>
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @if($ouvrage->gamme)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-ink-100 text-ink-600">
                                            {{ $ouvrage->gamme->nom }}
                                        </span>
                                    @endif
                                    @if($ouvrage->categorie)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-ink-100 text-ink-600">
                                            {{ $ouvrage->categorie->nom }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <svg class="w-4 h-4 text-ink-300 group-hover:text-amber-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $ouvrages->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-8 text-ink-400">
                    <p>Aucun ouvrage trouvé.</p>
                </div>
            @endif
        @endif

        {{-- Liste des composants --}}
        @if(($type === 'all' || $type === 'composants') && $composants->count() > 0)
            <div class="mt-6">
                <h3 class="text-sm font-semibold text-ink-700 mb-3">Composants trouvés</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($composants as $composant)
                        <a href="{{ route('composants.show', $composant->slug) }}" 
                           class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300 flex items-start p-4 gap-4">
                            
                            <div class="w-12 h-12 bg-ink-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-xl">🔩</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-ink-900 group-hover:text-amber-700 transition">
                                    {{ $composant->designation }}
                                </h4>
                                <p class="text-xs text-ink-400 mt-0.5">Réf. {{ $composant->reference }}</p>
                                @if($composant->matiere)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-ink-100 text-ink-600 mt-1">
                                        {{ $composant->matiere }}
                                    </span>
                                @endif
                            </div>

                            <svg class="w-4 h-4 text-ink-300 group-hover:text-amber-600 transition flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    @elseif($query || $categorie || $gamme)
        {{-- Aucun résultat --}}
        <div class="text-center py-16 bg-white rounded-xl border border-ink-200">
            <div class="text-ink-400">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-lg font-medium">Aucun résultat trouvé</p>
                <p class="text-sm mt-1">Essayez de modifier vos critères de recherche.</p>
            </div>
        </div>
    @else
        {{-- Message initial --}}
        <div class="text-center py-16 bg-white rounded-xl border border-ink-200">
            <div class="text-ink-400">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-lg font-medium">Que recherchez-vous ?</p>
                <p class="text-sm mt-1">Utilisez la barre de recherche ou les filtres ci-dessus.</p>
            </div>
        </div>
    @endif
</div>
@endsection