{{-- resources/views/public/ouvrages/show.blade.php --}}
@extends('layouts.app')

@section('title', $ouvrage->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-1.5 text-ink-400">›</span>
    <a href="{{ route('ouvrages.index') }}" class="hover:text-ink-700">Ouvrages</a>
    <span class="mx-1.5 text-ink-400">›</span>
    @if($ouvrage->categorie)
        <a href="{{ route('ouvrages.index', ['categorie' => $ouvrage->categorie->slug]) }}" class="hover:text-ink-700">{{ $ouvrage->categorie->nom }}</a>
        <span class="mx-1.5 text-ink-400">›</span>
    @endif
    <span class="text-ink-700 font-medium">{{ $ouvrage->nom }}</span>
@endsection

@section('content')
<div>
    {{-- ============================================================ --}}
    {{-- EN-TÊTE --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-ink-200 p-4 mb-4">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-xs font-semibold text-ink-500 bg-ink-100 px-2 py-0.5 tracking-wider">
                        {{ $ouvrage->reference }}
                    </span>
                    @if($ouvrage->est_actif)
                        <span class="text-[10px] font-semibold text-green-700 bg-green-50 px-2 py-0.5 border border-green-200 uppercase tracking-wider">
                            Actif
                        </span>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-ink-900">{{ $ouvrage->nom }}</h1>
                @if($ouvrage->description_courte)
                    <p class="text-sm text-ink-600 mt-1">{{ $ouvrage->description_courte }}</p>
                @endif
            </div>
            <div class="flex flex-wrap gap-1.5">
                @if($ouvrage->gamme)
                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                        {{ $ouvrage->gamme->nom }}
                    </span>
                @endif
                @if($ouvrage->categorie)
                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-medium bg-ink-100 text-ink-600 border border-ink-200 uppercase tracking-wider">
                        {{ $ouvrage->categorie->nom }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- CARROUSEL D'IMAGES --}}
    {{-- ============================================================ --}}
    @if($ouvrage->medias->count() > 0)
        <div class="mb-4">
            @include('public.partials.media-carousel', [
                'medias' => $ouvrage->medias->sortBy('pivot.ordre'),
                'carouselId' => 'ouvrage-' . $ouvrage->id,
                'large' => true,
            ])
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- DESCRIPTION TECHNIQUE --}}
    {{-- ============================================================ --}}
    @if($ouvrage->description_technique)
        <div class="bg-white border border-ink-200 mb-4">
            <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                <h2 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Description technique</h2>
            </div>
            <div class="p-4">
                <div class="prose prose-sm text-ink-600 max-w-none">{{ $ouvrage->description_technique }}</div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- CARACTÉRISTIQUES TECHNIQUES --}}
    {{-- ============================================================ --}}
    @if($ouvrage->caracteristiques->count())
        <div class="bg-white border border-ink-200 mb-4">
            <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                <h2 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Caractéristiques techniques</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-x divide-y divide-ink-100">
                @foreach($ouvrage->caracteristiques as $carac)
                    <div class="flex items-center justify-between px-3 py-2">
                        <span class="text-xs text-ink-500">{{ $carac->cle }}</span>
                        <span class="text-xs font-mono font-medium text-ink-900">
                            {{ $carac->valeur }}
                            @if($carac->unite)<span class="text-ink-400 ml-0.5">{{ $carac->unite }}</span>@endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- COMPOSITION AVEC VIGNETTES --}}
    {{-- ============================================================ --}}
    @if($ouvrage->composants->count())
        <div class="bg-white border border-ink-200 mb-4">
            <div class="px-3 py-2 bg-ink-50 border-b border-ink-200 flex items-center justify-between">
                <h2 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">
                    Composition
                    <span class="text-ink-400 font-normal ml-1">({{ $ouvrage->composants->count() }})</span>
                </h2>
                <a href="{{ route('ouvrages.composition', $ouvrage->slug) }}" 
                   class="text-xs font-medium text-amber-700 hover:text-amber-800 transition">
                    Détail complet →
                </a>
            </div>

            <div class="p-3 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($ouvrage->composants->sortBy('pivot.ordre') as $composant)
                    @php
                        $media = $composant->medias->firstWhere('est_principal', true) 
                              ?? $composant->medias->sortBy('pivot.ordre')->first();
                    @endphp

                    <a href="{{ route('composants.show', $composant->slug) }}"
                       class="group bg-white border border-ink-200 hover:border-amber-500 hover:shadow-md transition-all flex flex-col">

                        {{-- Thumbnail --}}
                        <div class="aspect-square bg-ink-50 border-b border-ink-100 overflow-hidden flex items-center justify-center relative">
                            @if($media)
                                <img src="{{ asset('storage/' . $media->chemin_fichier) }}" 
                                     alt="{{ $media->titre ?? $composant->designation }}"
                                     class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                            @else
                                <svg class="w-10 h-10 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif

                            {{-- Badge quantité --}}
                            <span class="absolute top-1.5 right-1.5 font-mono text-[10px] font-bold text-white bg-ink-900/80 backdrop-blur-sm px-1.5 py-0.5 tracking-wider">
                                ×{{ $composant->pivot->quantite }}
                            </span>
                        </div>

                        {{-- Contenu --}}
                        <div class="p-2.5 flex flex-col flex-1">

                            {{-- Référence + type --}}
                            <div class="flex items-start justify-between gap-2 mb-1.5 min-w-0">
                                <span class="font-mono text-[10px] font-bold text-ink-600 bg-ink-100 px-1.5 py-0.5 tracking-wider truncate max-w-[60%]">
                                    {{ $composant->reference }}
                                </span>
                                @if($composant->typeComposant)
                                    <span class="text-[10px] text-ink-400 uppercase tracking-wider font-semibold truncate max-w-[40%] text-right shrink-0">
                                        {{ $composant->typeComposant->nom }}
                                    </span>
                                @endif
                            </div>

                            {{-- Désignation --}}
                            <h3 class="text-xs font-semibold text-ink-900 leading-snug line-clamp-2 group-hover:text-amber-700 transition-colors">
                                {{ $composant->designation }}
                            </h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ACTIONS --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-ink-200">
        <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
            <h3 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Actions</h3>
        </div>
        <div class="p-3 grid grid-cols-1 md:grid-cols-3 gap-2">
            <a href="{{ route('ouvrages.composition', $ouvrage->slug) }}" 
               class="flex items-center justify-center px-4 py-2.5 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition text-xs font-semibold uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Composition
            </a>
            <a href="{{ route('ouvrages.print', $ouvrage->slug) }}" target="_blank"
               class="flex items-center justify-center px-4 py-2.5 bg-ink-100 text-ink-700 border border-ink-200 hover:bg-ink-200 transition text-xs font-semibold uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimer
            </a>
        </div>
    </div>
</div>
@endsection