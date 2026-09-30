{{-- resources/views/public/ouvrages/show.blade.php
     Direction "Blueprint" — badge gamme retiré, panneaux sombres cohérents
     avec composants/show, composition réutilise le partial composant-card. --}}
@extends('layouts.app')

@section('title', $ouvrage->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-1.5 text-ink-600">›</span>
    <a href="{{ route('ouvrages.index') }}" class="hover:text-fg transition">Ouvrages</a>
    <span class="mx-1.5 text-ink-600">›</span>
    @if($ouvrage->categorie)
        <a href="{{ route('ouvrages.index', ['categorie' => $ouvrage->categorie->slug]) }}" class="hover:text-fg transition">{{ $ouvrage->categorie->nom }}</a>
        <span class="mx-1.5 text-ink-600">›</span>
    @endif
    <span class="text-ink-200 font-medium">{{ $ouvrage->nom }}</span>
@endsection

@section('content')
<div>
    {{-- ============================================================ --}}
    {{-- EN-TÊTE --}}
    {{-- ============================================================ --}}
    <div class="relative bg-ink-900 border border-ink-700 p-4 mb-4">
        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>

        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-xs font-semibold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 tracking-wider">
                        {{ $ouvrage->reference }}
                    </span>
                    @if($ouvrage->est_actif)
                        <span class="font-mono text-[10px] font-semibold text-green-400 bg-green-500/10 px-2 py-0.5 border border-green-500/30 uppercase tracking-wider">
                            Actif
                        </span>
                    @endif
                </div>
                <h1 class="font-display text-xl font-bold text-fg">{{ $ouvrage->nom }}</h1>
                @if($ouvrage->description_courte)
                    <p class="text-sm text-ink-300 mt-1">{{ $ouvrage->description_courte }}</p>
                @endif
            </div>
            <div class="flex flex-wrap gap-1.5">
                @if($ouvrage->categorie)
                    <span class="font-mono inline-flex items-center px-2 py-1 text-[10px] font-medium bg-ink-800 text-ink-300 border border-ink-700 uppercase tracking-wider">
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
        <div class="bg-ink-900 border border-ink-800 mb-4">
            <div class="px-3 py-2 bg-ink-950 border-b border-ink-800">
                <h2 class="font-mono text-[10px] font-bold text-ink-500 uppercase tracking-widest">Description technique</h2>
            </div>
            <div class="p-4">
                <div class="prose prose-sm prose-invert text-ink-300 max-w-none">{{ $ouvrage->description_technique }}</div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- CARACTÉRISTIQUES TECHNIQUES --}}
    {{-- ============================================================ --}}
    @if($ouvrage->caracteristiques->count())
        <div class="bg-ink-900 border border-ink-800 mb-4">
            <div class="px-3 py-2 bg-ink-950 border-b border-ink-800">
                <h2 class="font-mono text-[10px] font-bold text-ink-500 uppercase tracking-widest">Caractéristiques techniques</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-x divide-y divide-ink-800">
                @foreach($ouvrage->caracteristiques as $carac)
                    <div class="flex items-center justify-between px-3 py-2">
                        <span class="text-xs text-ink-500">{{ $carac->cle }}</span>
                        <span class="text-xs font-mono font-medium text-ink-100">
                            {{ $carac->valeur }}
                            @if($carac->unite)<span class="text-ink-500 ml-0.5">{{ $carac->unite }}</span>@endif
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
        <div class="bg-ink-900 border border-ink-800 mb-4">
            <div class="px-3 py-2 bg-ink-950 border-b border-ink-800 flex items-center justify-between">
                <h2 class="font-mono text-[10px] font-bold text-ink-500 uppercase tracking-widest">
                    Composition
                    <span class="text-ink-600 font-normal ml-1">({{ $ouvrage->composants->count() }})</span>
                </h2>
                <a href="{{ route('ouvrages.composition', $ouvrage->slug) }}"
                   class="font-mono text-[11px] font-medium text-amber-400 hover:text-amber-600 transition">
                    Détail complet →
                </a>
            </div>

            <div class="p-3 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($ouvrage->composants->sortBy('pivot.ordre') as $composant)
                    @include('public.partials.composant-card', ['composant' => $composant, 'quantite' => $composant->pivot->quantite])
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ACTIONS --}}
    {{-- ============================================================ --}}
    <div class="bg-ink-900 border border-ink-800">
        <div class="px-3 py-2 bg-ink-950 border-b border-ink-800">
            <h3 class="font-mono text-[10px] font-bold text-ink-500 uppercase tracking-widest">Actions</h3>
        </div>
        <div class="p-3 grid grid-cols-1 md:grid-cols-3 gap-2">
            <a href="{{ route('ouvrages.composition', $ouvrage->slug) }}"
               class="flex items-center justify-center px-4 py-2.5 bg-amber-500/10 text-amber-400 border border-amber-500/30 hover:bg-amber-500/20 transition text-xs font-semibold uppercase tracking-wider font-mono">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Composition
            </a>
            <a href="{{ route('ouvrages.print', $ouvrage->slug) }}" target="_blank"
               class="flex items-center justify-center px-4 py-2.5 bg-ink-800 text-ink-300 border border-ink-700 hover:bg-ink-700 hover:text-fg transition text-xs font-semibold uppercase tracking-wider font-mono">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimer
            </a>
        </div>
    </div>
</div>
@endsection