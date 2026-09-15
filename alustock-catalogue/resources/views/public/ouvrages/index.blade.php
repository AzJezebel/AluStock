{{-- resources/views/public/ouvrages/index.blade.php --}}
@extends('layouts.app')

@section('title', isset($categorieCourante) ? $categorieCourante->nom . ' - AluStock' : (isset($gammeCourante) ? $gammeCourante->nom . ' - AluStock' : 'Tous les ouvrages - AluStock'))

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-slate-700">Accueil</a>
    <span class="mx-1.5 text-slate-400">›</span>
    @if(isset($categorieCourante))
        <a href="{{ route('categories.index') }}" class="hover:text-slate-700">Catégories</a>
        <span class="mx-1.5 text-slate-400">›</span>
        <span class="text-slate-700 font-medium">{{ $categorieCourante->nom }}</span>
    @elseif(isset($gammeCourante))
        <a href="{{ route('gammes.index') }}" class="hover:text-slate-700">Gammes</a>
        <span class="mx-1.5 text-slate-400">›</span>
        <span class="text-slate-700 font-medium">{{ $gammeCourante->nom }}</span>
    @else
        <span class="text-slate-700 font-medium">Tous les ouvrages</span>
    @endif
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-4">
        @if(isset($categorieCourante))
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                @if($categorieCourante->icone)<span>{{ $categorieCourante->icone }}</span>@endif
                {{ $categorieCourante->nom }}
            </h1>
            @if($categorieCourante->description)
                <p class="text-slate-500 text-sm mt-0.5">{{ $categorieCourante->description }}</p>
            @endif
        @elseif(isset($gammeCourante))
            <h1 class="text-xl font-bold text-slate-900">{{ $gammeCourante->nom }}</h1>
            @if($gammeCourante->description)
                <p class="text-slate-500 text-sm mt-0.5">{{ $gammeCourante->description }}</p>
            @endif
        @else
            <h1 class="text-xl font-bold text-slate-900">Tous les ouvrages</h1>
            <p class="text-slate-500 text-sm mt-0.5">Catalogue technique complet</p>
        @endif
        <div class="mt-1 text-xs text-slate-500 font-mono">
            {{ $ouvrages->total() }} RÉFÉRENCE(S)
        </div>
    </div>

    {{-- Filtres actifs --}}
    @if(isset($categorieCourante) || isset($gammeCourante))
        <div class="mb-4 flex flex-wrap gap-2 text-xs">
            <a href="{{ route('ouvrages.index') }}" 
               class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200 transition">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Effacer les filtres
            </a>
            @if(isset($categorieCourante))
                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200">
                    Catégorie : {{ $categorieCourante->nom }}
                </span>
            @endif
            @if(isset($gammeCourante))
                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 border border-blue-200">
                    Gamme : {{ $gammeCourante->nom }}
                </span>
            @endif
        </div>
    @endif

    {{-- Grille compacte --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-px bg-slate-200 border border-slate-200">
            @foreach($ouvrages as $ouvrage)
                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}"
                   class="group bg-white hover:bg-slate-50 transition-colors p-2.5 flex flex-col">

                    {{-- Référence + statut --}}
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-mono text-[10px] font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.5 tracking-wider">
                            {{ $ouvrage->reference }}
                        </span>
                        @if($ouvrage->categorie)
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">
                                {{ $ouvrage->categorie->nom }}
                            </span>
                        @endif
                    </div>

                    {{-- Nom --}}
                    <h3 class="text-xs font-semibold text-slate-900 leading-tight line-clamp-2 group-hover:text-blue-700 transition-colors min-h-[2rem]">
                        {{ $ouvrage->nom }}
                    </h3>

                    {{-- Description courte (tronquée) --}}
                    @if($ouvrage->description_courte)
                        <p class="text-[10px] text-slate-500 line-clamp-2 mt-1 leading-snug">
                            {{ $ouvrage->description_courte }}
                        </p>
                    @endif

                    {{-- Footer --}}
                    <div class="mt-auto pt-1.5 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400">
                            {{ $ouvrage->gamme?->nom ?? '—' }}
                        </span>
                        <span class="text-[10px] text-blue-700 opacity-0 group-hover:opacity-100 transition-opacity font-medium">
                            →
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $ouvrages->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-white border border-slate-200">
            <p class="text-slate-500 text-sm">Aucun ouvrage disponible.</p>
        </div>
    @endif
</div>
@endsection