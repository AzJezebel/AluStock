{{-- resources/views/public/composants/show.blade.php --}}
@extends('layouts.app')

@section('title', $composant->designation . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-1.5 text-ink-400">›</span>
    <a href="{{ route('composants.index') }}" class="hover:text-ink-700">Composants</a>
    <span class="mx-1.5 text-ink-400">›</span>
    <span class="text-ink-700 font-medium">{{ $composant->designation }}</span>
@endsection

@section('content')
<div>
    {{-- En-tête compact --}}
    <div class="bg-white border border-ink-200 p-4 mb-4">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-xs font-semibold text-ink-500 bg-ink-100 px-2 py-0.5 tracking-wider">
                        {{ $composant->reference }}
                    </span>
                    @if($composant->est_disponible)
                        <span class="text-[10px] font-semibold text-green-700 bg-green-50 px-2 py-0.5 border border-green-200 uppercase tracking-wider">
                            Disponible
                        </span>
                    @else
                        <span class="text-[10px] font-semibold text-red-700 bg-red-50 px-2 py-0.5 border border-red-200 uppercase tracking-wider">
                            Indisponible
                        </span>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-ink-900">{{ $composant->designation }}</h1>
                @if($composant->matiere)
                    <p class="text-xs text-ink-500 mt-1">Matière : <span class="text-ink-700 font-medium">{{ $composant->matiere }}</span></p>
                @endif
            </div>
            <div class="flex flex-wrap gap-1.5">
                @if($composant->typeComposant)
                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wider">
                        {{ $composant->typeComposant->nom }}
                    </span>
                @endif
                @if($composant->gamme)
                    <span class="inline-flex items-center px-2 py-1 text-[10px] font-medium bg-ink-100 text-ink-600 border border-ink-200 uppercase tracking-wider">
                        {{ $composant->gamme->nom }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Layout 2 colonnes : carrousel + specs --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        
        {{-- Carrousel --}}
        @if($composant->medias->count() > 0)
            <div>
                @include('public.partials.media-carousel', [
                    'medias' => $composant->medias->sortBy('pivot.ordre'),
                    'carouselId' => 'composant-' . $composant->id,
                    'large' => true,
                ])
            </div>
        @else
            <div class="bg-white border border-ink-200 p-12 flex items-center justify-center">
                <div class="text-center text-ink-300">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-xs uppercase tracking-wider">Aucune image</p>
                </div>
            </div>
        @endif

        {{-- Specs --}}
        <div class="space-y-3">

            {{-- Dimensions --}}
            @if($composant->longueur_barre_mm || $composant->section_largeur_mm || $composant->section_hauteur_mm || $composant->epaisseur_paroi_mm)
                <div class="bg-white border border-ink-200">
                    <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                        <h3 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Dimensions</h3>
                    </div>
                    <table class="w-full text-xs">
                        @if($composant->longueur_barre_mm)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Longueur</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->longueur_barre_mm }} mm</td>
                            </tr>
                        @endif
                        @if($composant->section_largeur_mm)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Largeur section</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->section_largeur_mm }} mm</td>
                            </tr>
                        @endif
                        @if($composant->section_hauteur_mm)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Hauteur section</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->section_hauteur_mm }} mm</td>
                            </tr>
                        @endif
                        @if($composant->epaisseur_paroi_mm)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Épaisseur paroi</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->epaisseur_paroi_mm }} mm</td>
                            </tr>
                        @endif
                    </table>
                </div>
            @endif

            {{-- Propriétés --}}
            @if($composant->poids_lineaire_kg_m || $composant->poids_lineaire_lbs_ft || $composant->moment_inertie_cm4 || $composant->perimetre_mm)
                <div class="bg-white border border-ink-200">
                    <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                        <h3 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Propriétés mécaniques</h3>
                    </div>
                    <table class="w-full text-xs">
                        @if($composant->poids_lineaire_kg_m)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Poids (KG/M)</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->poids_lineaire_kg_m }} kg/m</td>
                            </tr>
                        @endif
                        @if($composant->poids_lineaire_lbs_ft)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Poids (WT/FT)</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->poids_lineaire_lbs_ft }} lbs/ft</td>
                            </tr>
                        @endif
                        @if($composant->moment_inertie_cm4)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Moment d'inertie</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->moment_inertie_cm4 }} cm⁴</td>
                            </tr>
                        @endif
                        @if($composant->perimetre_mm)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">Périmètre</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">{{ $composant->perimetre_mm }} mm</td>
                            </tr>
                        @endif
                    </table>
                </div>
            @endif

            {{-- Caractéristiques EAV --}}
            @if($composant->caracteristiques->count())
                <div class="bg-white border border-ink-200">
                    <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                        <h3 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Caractéristiques</h3>
                    </div>
                    <table class="w-full text-xs">
                        @foreach($composant->caracteristiques as $carac)
                            <tr class="border-b border-ink-100 last:border-0">
                                <td class="px-3 py-1.5 text-ink-500">{{ $carac->cle }}</td>
                                <td class="px-3 py-1.5 text-right font-mono font-medium text-ink-900">
                                    {{ $carac->valeur }}
                                    @if($carac->unite)<span class="text-ink-400">{{ $carac->unite }}</span>@endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Finitions --}}
    @if($composant->finitions->count())
        <div class="bg-white border border-ink-200 mb-4">
            <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                <h2 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Finitions disponibles</h2>
            </div>
            <div class="p-3 flex flex-wrap gap-2">
                @foreach($composant->finitions as $finition)
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium border
                        {{ $finition->pivot->est_par_defaut ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-ink-50 text-ink-600 border-ink-200' }}">
                        @if($finition->code_ral)
                            <span class="w-3 h-3 inline-block border border-ink-300 mr-1.5" 
                                  style="background-color: #{{ $finition->code_ral }}"></span>
                        @endif
                        {{ $finition->nom }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Utilisé dans --}}
    @if(isset($ouvrages) && $ouvrages->count())
        <div class="bg-white border border-ink-200">
            <div class="px-3 py-2 bg-ink-50 border-b border-ink-200">
                <h2 class="text-[10px] font-bold text-ink-600 uppercase tracking-wider">Utilisé dans</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 divide-x divide-y divide-ink-100">
                @foreach($ouvrages as $ouvrage)
                    <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                       class="flex items-center justify-between p-3 hover:bg-ink-50 transition group">
                        <div>
                            <p class="text-xs font-semibold text-ink-900 group-hover:text-amber-700">{{ $ouvrage->nom }}</p>
                            <p class="text-[10px] text-ink-400 font-mono">{{ $ouvrage->reference }}</p>
                        </div>
                        <svg class="w-3 h-3 text-ink-300 group-hover:text-amber-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection