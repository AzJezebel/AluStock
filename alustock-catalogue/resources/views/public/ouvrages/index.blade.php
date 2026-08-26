{{-- resources/views/public/ouvrages/index.blade.php --}}
@extends('layouts.app')

@section('title', isset($categorieCourante) ? $categorieCourante->nom . ' - AluStock' : 'Tous les ouvrages - AluStock')

@section('content')
<div>
    {{-- En-tête avec filtre actif --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                @if(isset($categorieCourante))
                    <nav class="flex items-center text-sm text-aluminum-500 mb-2">
                        <a href="{{ route('home') }}" class="hover:text-primary-600">Accueil</a>
                        <span class="mx-2">›</span>
                        <a href="{{ route('categories.index') }}" class="hover:text-primary-600">Catégories</a>
                        <span class="mx-2">›</span>
                        <span class="text-aluminum-700 font-medium">{{ $categorieCourante->nom }}</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-aluminum-900 flex items-center gap-3">
                        @if($categorieCourante->icone)
                            <span>{{ $categorieCourante->icone }}</span>
                        @endif
                        {{ $categorieCourante->nom }}
                    </h1>
                    @if($categorieCourante->description)
                        <p class="text-aluminum-500 text-sm mt-1">{{ $categorieCourante->description }}</p>
                    @endif
                @else
                    <h1 class="text-2xl font-bold text-aluminum-900">Tous les ouvrages</h1>
                    <p class="text-aluminum-500 text-sm mt-1">Découvrez l'ensemble de nos ouvrages en aluminium.</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0 text-sm text-aluminum-400">
                <span class="font-medium text-aluminum-700">{{ $ouvrages->total() }}</span> ouvrage(s)
            </div>
        </div>
    </div>

    {{-- Filtres (optionnel) --}}
    <div class="mb-6 flex flex-wrap gap-3">
        @if(isset($categorieCourante))
            <a href="{{ route('ouvrages.index') }}" 
               class="inline-flex items-center px-3 py-1.5 bg-aluminum-100 text-aluminum-600 text-sm rounded-lg hover:bg-aluminum-200 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Effacer le filtre
            </a>
        @endif
    </div>

    {{-- Grille des ouvrages --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ouvrages as $ouvrage)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-aluminum-200 hover:border-primary-200">
                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <h3 class="text-base font-semibold text-aluminum-900 group-hover:text-primary-600 transition flex-1">
                                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}">
                                    {{ $ouvrage->nom }}
                                </a>
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700 ml-2">
                                {{ $ouvrage->reference }}
                            </span>
                        </div>
                        
                        @if($ouvrage->description_courte)
                            <p class="text-sm text-aluminum-500 mt-2 line-clamp-2">
                                {{ $ouvrage->description_courte }}
                            </p>
                        @endif

                        <div class="flex flex-wrap gap-2 mt-3">
                            @if($ouvrage->gamme)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-aluminum-100 text-aluminum-600">
                                    {{ $ouvrage->gamme->nom }}
                                </span>
                            @endif
                            @if($ouvrage->categorie)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-aluminum-100 text-aluminum-600">
                                    {{ $ouvrage->categorie->nom }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-aluminum-100">
                            <span class="text-xs text-aluminum-400">
                                {{ $ouvrage->created_at?->format('d/m/Y') ?? 'N/A' }}
                            </span>
                            <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                               class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition">
                                Voir détails
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $ouvrages->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-aluminum-200">
            <p class="text-aluminum-500">Aucun ouvrage disponible dans cette catégorie.</p>
        </div>
    @endif
</div>
@endsection