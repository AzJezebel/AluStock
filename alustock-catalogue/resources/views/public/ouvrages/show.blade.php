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
    {{-- En-tête compact --}}
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

    {{-- Carrousel --}}
    @if($ouvrage->medias->count() > 0)
        <div class="mb-4">
            @include('public.partials.media-carousel', [
                'medias' => $ouvrage->medias->sortBy('pivot.ordre'),
                'carouselId' => 'ouvrage-' . $ouvrage->id,
                'large' => true,
            ])
        </div>
    @endif

    {{-- Description technique --}}
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

    {{-- Caractéristiques --}}
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

    {{-- Composition --}}
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
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-ink-50 border-b border-ink-200">
                        <tr>
                            <th class="px-3 py-2 text-left font-bold text-ink-500 uppercase tracking-wider">#</th>
                            <th class="px-3 py-2 text-left font-bold text-ink-500 uppercase tracking-wider">Composant</th>
                            <th class="px-3 py-2 text-left font-bold text-ink-500 uppercase tracking-wider">Référence</th>
                            <th class="px-3 py-2 text-right font-bold text-ink-500 uppercase tracking-wider">Qté</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @foreach($ouvrage->composants as $composant)
                            <tr class="hover:bg-ink-50 transition">
                                <td class="px-3 py-2 text-ink-400 font-mono">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="px-3 py-2 text-ink-900 font-medium">
                                    <a href="{{ route('composants.show', $composant->slug) }}" class="hover:text-amber-700 transition">
                                        {{ $composant->designation }}
                                    </a>
                                </td>
                                <td class="px-3 py-2 text-ink-500 font-mono">{{ $composant->reference }}</td>
                                <td class="px-3 py-2 text-ink-700 text-right font-mono font-medium">
                                    {{ $composant->pivot->quantite }} <span class="text-ink-400">{{ $composant->pivot->unite }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Actions --}}
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
            <a href="#" 
               class="flex items-center justify-center px-4 py-2.5 bg-amber-700 text-white hover:bg-amber-800 transition text-xs font-semibold uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Télécharger PDF
            </a>
        </div>
    </div>
</div>
@endsection