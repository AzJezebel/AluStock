{{-- resources/views/public/composants/show.blade.php --}}
@extends('layouts.app')

@section('title', $composant->designation . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-2">›</span>
    <a href="{{ route('composants.index') }}" class="hover:text-ink-700">Composants</a>
    <span class="mx-2">›</span>
    <span class="text-ink-700 font-medium">{{ $composant->designation }}</span>
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-ink-900">{{ $composant->designation }}</h1>
                <p class="text-sm text-ink-500 mt-1">Référence : {{ $composant->reference }}</p>
                @if($composant->matiere)
                    <p class="text-sm text-ink-600 mt-1">Matière : {{ $composant->matiere }}</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                @if($composant->typeComposant)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">{{ $composant->typeComposant->nom }}</span>
                @endif
                @if($composant->gamme)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ink-100 text-ink-600">{{ $composant->gamme->nom }}</span>
                @endif
                @if($composant->est_disponible)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">Disponible</span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">Indisponible</span>
                @endif
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- LAYOUT : CARROUSEL + SPECS CÔTE À CÔTE --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        {{-- Carrousel d'images --}}
        @if($composant->medias->count() > 0)
            <div>
                @include('public.partials.media-carousel', [
                    'medias' => $composant->medias->sortBy('pivot.ordre'),
                    'carouselId' => 'composant-' . $composant->id,
                    'large' => true,
                ])
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-12 flex items-center justify-center">
                <div class="text-center text-ink-300">
                    <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm">Aucune image disponible</p>
                </div>
            </div>
        @endif

        {{-- Caractéristiques techniques --}}
        <div class="space-y-6">
            {{-- Dimensions --}}
            @if($composant->longueur_barre_mm || $composant->section_largeur_mm || $composant->section_hauteur_mm || $composant->epaisseur_paroi_mm || $composant->poids_lineaire_kg_m)
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6">
                    <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-3">Dimensions</h3>
                    <div class="space-y-2 text-sm">
                        @if($composant->longueur_barre_mm)
                            <div class="flex justify-between"><span class="text-ink-500">Longueur</span><span class="text-ink-700 font-medium">{{ $composant->longueur_barre_mm }} mm</span></div>
                        @endif
                        @if($composant->section_largeur_mm)
                            <div class="flex justify-between"><span class="text-ink-500">Largeur section</span><span class="text-ink-700 font-medium">{{ $composant->section_largeur_mm }} mm</span></div>
                        @endif
                        @if($composant->section_hauteur_mm)
                            <div class="flex justify-between"><span class="text-ink-500">Hauteur section</span><span class="text-ink-700 font-medium">{{ $composant->section_hauteur_mm }} mm</span></div>
                        @endif
                        @if($composant->epaisseur_paroi_mm)
                            <div class="flex justify-between"><span class="text-ink-500">Épaisseur paroi</span><span class="text-ink-700 font-medium">{{ $composant->epaisseur_paroi_mm }} mm</span></div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Poids + inertie + périmètre --}}
            @if($composant->poids_lineaire_kg_m || $composant->poids_lineaire_lbs_ft || $composant->moment_inertie_cm4 || $composant->perimetre_mm)
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6">
                    <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-3">Propriétés</h3>
                    <div class="space-y-2 text-sm">
                        @if($composant->poids_lineaire_kg_m)
                            <div class="flex justify-between"><span class="text-ink-500">KG/M</span><span class="text-ink-700 font-medium">{{ $composant->poids_lineaire_kg_m }} kg/m</span></div>
                        @endif
                        @if($composant->poids_lineaire_lbs_ft)
                            <div class="flex justify-between"><span class="text-ink-500">WT/FT</span><span class="text-ink-700 font-medium">{{ $composant->poids_lineaire_lbs_ft }} lbs/ft</span></div>
                        @endif
                        @if($composant->moment_inertie_cm4)
                            <div class="flex justify-between"><span class="text-ink-500">IN</span><span class="text-ink-700 font-medium">{{ $composant->moment_inertie_cm4 }} cm⁴</span></div>
                        @endif
                        @if($composant->perimetre_mm)
                            <div class="flex justify-between"><span class="text-ink-500">PERIM.</span><span class="text-ink-700 font-medium">{{ $composant->perimetre_mm }} mm</span></div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Caractéristiques EAV --}}
            @if($composant->caracteristiques->count())
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6">
                    <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-3">Caractéristiques</h3>
                    <div class="space-y-2 text-sm">
                        @foreach($composant->caracteristiques as $carac)
                            <div class="flex justify-between">
                                <span class="text-ink-500">{{ $carac->cle }}</span>
                                <span class="text-ink-700 font-medium">
                                    {{ $carac->valeur }}
                                    @if($carac->unite)<span class="text-xs text-ink-400">{{ $carac->unite }}</span>@endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Finitions --}}
    @if($composant->finitions->count())
        <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-ink-900 mb-4">Finitions disponibles</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($composant->finitions as $finition)
                    <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium 
                        {{ $finition->pivot->est_par_defaut ? 'bg-amber-100 text-amber-700 border-2 border-amber-300' : 'bg-ink-100 text-ink-600' }}">
                        {{ $finition->nom }}
                        @if($finition->code_ral)
                            <span class="ml-2 w-4 h-4 rounded-full inline-block border border-ink-300" 
                                  style="background-color: #{{ $finition->code_ral }}"></span>
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Utilisé dans --}}
    @if(isset($ouvrages) && $ouvrages->count())
        <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6">
            <h2 class="text-lg font-semibold text-ink-900 mb-4">Utilisé dans</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($ouvrages as $ouvrage)
                    <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                       class="flex items-center justify-between p-3 bg-ink-50 rounded-lg hover:bg-amber-50 transition group">
                        <div>
                            <p class="text-sm font-medium text-ink-900 group-hover:text-amber-700">{{ $ouvrage->nom }}</p>
                            <p class="text-xs text-ink-400">{{ $ouvrage->gamme?->nom ?? 'Sans gamme' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-ink-300 group-hover:text-amber-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection