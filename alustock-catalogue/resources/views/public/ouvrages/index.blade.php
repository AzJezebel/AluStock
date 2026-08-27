{{-- resources/views/public/ouvrages/index.blade.php --}}
@extends('layouts.app')

@section('title', isset($categorieCourante) ? $categorieCourante->nom . ' - AluStock' : (isset($gammeCourante) ? $gammeCourante->nom . ' - AluStock' : 'Tous les ouvrages - AluStock'))

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-2">›</span>
    @if(isset($categorieCourante))
        <a href="{{ route('categories.index') }}" class="hover:text-ink-700">Catégories</a>
        <span class="mx-2">›</span>
        <span class="text-ink-700 font-medium">{{ $categorieCourante->nom }}</span>
    @elseif(isset($gammeCourante))
        <a href="{{ route('gammes.index') }}" class="hover:text-ink-700">Gammes</a>
        <span class="mx-2">›</span>
        <span class="text-ink-700 font-medium">{{ $gammeCourante->nom }}</span>
    @else
        <span class="text-ink-700 font-medium">Tous les ouvrages</span>
    @endif
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-6">
        @if(isset($categorieCourante))
            <h1 class="text-2xl font-bold text-ink-900 flex items-center gap-3">
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
            <p class="text-ink-500 text-sm mt-1">Découvrez l'ensemble de nos ouvrages en aluminium.</p>
        @endif
        <div class="mt-2 text-sm text-ink-400">
            <span class="font-medium text-ink-700">{{ $ouvrages->total() }}</span> ouvrage(s)
        </div>
    </div>

    {{-- Filtres actifs --}}
    @if(isset($categorieCourante) || isset($gammeCourante))
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('ouvrages.index') }}" 
               class="inline-flex items-center px-3 py-1.5 bg-ink-100 text-ink-600 text-sm rounded-lg hover:bg-ink-200 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Effacer tous les filtres
            </a>
            @if(isset($categorieCourante))
                <span class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 text-sm rounded-lg">
                    Catégorie : {{ $categorieCourante->nom }}
                </span>
            @endif
            @if(isset($gammeCourante))
                <span class="inline-flex items-center px-3 py-1.5 bg-amber-50 text-amber-700 text-sm rounded-lg">
                    Gamme : {{ $gammeCourante->nom }}
                </span>
            @endif
        </div>
    @endif

    {{-- Grille --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ouvrages as $ouvrage)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300">
                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <h3 class="text-base font-semibold text-ink-900 group-hover:text-amber-700 transition flex-1">
                                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}">{{ $ouvrage->nom }}</a>
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ml-2 whitespace-nowrap">
                                {{ $ouvrage->reference }}
                            </span>
                        </div>
                        @if($ouvrage->description_courte)
                            <p class="text-sm text-ink-500 mt-2 line-clamp-2">{{ $ouvrage->description_courte }}</p>
                        @endif
                        <div class="flex flex-wrap gap-2 mt-3">
                            @if($ouvrage->gamme)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-ink-100 text-ink-600">{{ $ouvrage->gamme->nom }}</span>
                            @endif
                            @if($ouvrage->categorie)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-ink-100 text-ink-600">{{ $ouvrage->categorie->nom }}</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-ink-100">
                            <span class="text-xs text-ink-400">{{ $ouvrage->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                            <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                               class="inline-flex items-center text-sm font-medium text-amber-700 hover:text-amber-800 transition">
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
        <div class="mt-6">{{ $ouvrages->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-ink-200">
            <p class="text-ink-500">Aucun ouvrage disponible.</p>
        </div>
    @endif
</div>
@endsection