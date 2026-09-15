{{-- resources/views/public/ouvrages/index.blade.php --}}
@extends('layouts.app')

@section('title', isset($categorieCourante) ? $categorieCourante->nom . ' - AluStock' : (isset($gammeCourante) ? $gammeCourante->nom . ' - AluStock' : 'Tous les ouvrages - AluStock'))

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-1.5 text-ink-400">›</span>
    @if(isset($categorieCourante))
        <a href="{{ route('categories.index') }}" class="hover:text-ink-700">Catégories</a>
        <span class="mx-1.5 text-ink-400">›</span>
        <span class="text-ink-700 font-medium">{{ $categorieCourante->nom }}</span>
    @elseif(isset($gammeCourante))
        <a href="{{ route('gammes.index') }}" class="hover:text-ink-700">Gammes</a>
        <span class="mx-1.5 text-ink-400">›</span>
        <span class="text-ink-700 font-medium">{{ $gammeCourante->nom }}</span>
    @else
        <span class="text-ink-700 font-medium">Tous les ouvrages</span>
    @endif
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-5 pb-3 border-b border-ink-200">
        @if(isset($categorieCourante))
            <h1 class="text-2xl font-bold text-ink-900 flex items-center gap-2">
                @if($categorieCourante->icone)<span>{{ $categorieCourante->icone }}</span>@endif
                {{ $categorieCourante->nom }}
            </h1>
            @if($categorieCourante->description)
                <p class="text-ink-500 text-sm mt-1">{{ $categorieCourante->description }}</p>
            @endif
        @elseif(isset($gammeCourante))
            <h1 class="text-2xl font-bold text-ink-900">{{ $gammeCourante->nom }}</h1>
            @if($gammeCourante->description)
                <p class="text-ink-500 text-sm mt-1">{{ $gammeCourante->description }}</p>
            @endif
        @else
            <h1 class="text-2xl font-bold text-ink-900">Tous les ouvrages</h1>
            <p class="text-ink-500 text-sm mt-1">Catalogue technique complet</p>
        @endif
        <div class="mt-2 text-xs text-ink-500 font-mono tracking-wider">
            {{ $ouvrages->total() }} RÉFÉRENCE(S)
        </div>
    </div>

    {{-- Filtres actifs --}}
    @if(isset($categorieCourante) || isset($gammeCourante))
        <div class="mb-4 flex flex-wrap gap-2 text-xs">
            <a href="{{ route('ouvrages.index') }}" 
               class="inline-flex items-center px-3 py-1.5 bg-ink-100 text-ink-600 border border-ink-200 hover:bg-ink-200 transition">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Effacer les filtres
            </a>
            @if(isset($categorieCourante))
                <span class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200">
                    Catégorie : {{ $categorieCourante->nom }}
                </span>
            @endif
            @if(isset($gammeCourante))
                <span class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200">
                    Gamme : {{ $gammeCourante->nom }}
                </span>
            @endif
        </div>
    @endif

    {{-- Grille --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
            @foreach($ouvrages as $ouvrage)
                @php
                    $media = $ouvrage->medias->firstWhere('est_principal', true) 
                          ?? $ouvrage->medias->sortBy('pivot.ordre')->first();
                @endphp
                
                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}"
                   class="group bg-white border border-ink-200 hover:border-amber-500 hover:shadow-md transition-all flex flex-col">

                    {{-- Thumbnail --}}
                    <div class="aspect-square bg-ink-50 border-b border-ink-100 overflow-hidden flex items-center justify-center">
                        @if($media)
                            <img src="{{ asset('storage/' . $media->chemin_fichier) }}" 
                                 alt="{{ $media->titre ?? $ouvrage->nom }}"
                                 class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                        @else
                            <svg class="w-10 h-10 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="p-3 flex flex-col flex-1">

                        {{-- Référence + catégorie --}}
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono text-[10px] font-bold text-ink-600 bg-ink-100 px-1.5 py-0.5 tracking-wider">
                                {{ $ouvrage->reference }}
                            </span>
                            @if($ouvrage->categorie)
                                <span class="text-[10px] text-ink-400 uppercase tracking-wider font-semibold">
                                    {{ $ouvrage->categorie->nom }}
                                </span>
                            @endif
                        </div>

                        {{-- Nom --}}
                        <h3 class="text-sm font-semibold text-ink-900 leading-snug line-clamp-2 group-hover:text-amber-700 transition-colors">
                            {{ $ouvrage->nom }}
                        </h3>

                        {{-- Description courte --}}
                        @if($ouvrage->description_courte)
                            <p class="text-xs text-ink-500 line-clamp-2 mt-1.5 leading-snug">
                                {{ $ouvrage->description_courte }}
                            </p>
                        @endif

                        {{-- Footer --}}
                        <div class="mt-auto pt-2 border-t border-ink-100 flex items-center justify-between">
                            <span class="text-[10px] text-ink-500 font-medium truncate">
                                {{ $ouvrage->gamme?->nom ?? '—' }}
                            </span>
                            <span class="text-[10px] text-amber-700 font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap ml-1">
                                Voir →
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-5">{{ $ouvrages->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-white border border-ink-200">
            <p class="text-ink-500 text-sm">Aucun ouvrage disponible.</p>
        </div>
    @endif
</div>
@endsection