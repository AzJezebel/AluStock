{{-- resources/views/public/composants/show.blade.php --}}
@extends('layouts.app')

@section('title', $composant->designation . ' - AluStock')

@section('content')
<div>
    {{-- Fil d'Ariane --}}
    <nav class="flex items-center text-sm text-aluminum-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary-600">Accueil</a>
        <span class="mx-2">›</span>
        <a href="{{ route('composants.index') }}" class="hover:text-primary-600">Composants</a>
        <span class="mx-2">›</span>
        <span class="text-aluminum-700 font-medium">{{ $composant->designation }}</span>
    </nav>

    {{-- En-tête --}}
    <div class="bg-white rounded-xl shadow-sm border border-aluminum-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-aluminum-900">{{ $composant->designation }}</h1>
                <p class="text-sm text-aluminum-500 mt-1">Référence : {{ $composant->reference }}</p>
                @if($composant->matiere)
                    <p class="text-sm text-aluminum-600 mt-1">Matière : {{ $composant->matiere }}</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                @if($composant->typeComposant)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary-700">
                        {{ $composant->typeComposant->nom }}
                    </span>
                @endif
                @if($composant->gamme)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-aluminum-100 text-aluminum-600">
                        {{ $composant->gamme->nom }}
                    </span>
                @endif
                @if($composant->est_disponible)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                        Disponible
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700">
                        Indisponible
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Colonne principale --}}
        <div class="lg:col-span-2">
            {{-- Caractéristiques techniques --}}
            @if($composant->caracteristiques->count())
                <div class="bg-white rounded-xl shadow-sm border border-aluminum-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-aluminum-900 mb-4">Caractéristiques techniques</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($composant->caracteristiques as $carac)
                            <div class="flex items-center justify-between p-3 bg-aluminum-50 rounded-lg">
                                <span class="text-sm text-aluminum-600">{{ $carac->cle }}</span>
                                <span class="text-sm font-medium text-aluminum-900">
                                    {{ $carac->valeur }}
                                    @if($carac->unite)
                                        <span class="text-xs text-aluminum-400">{{ $carac->unite }}</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Finitions --}}
            @if($composant->finitions->count())
                <div class="bg-white rounded-xl shadow-sm border border-aluminum-200 p-6">
                    <h2 class="text-lg font-semibold text-aluminum-900 mb-4">Finitions disponibles</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($composant->finitions as $finition)
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium 
                                {{ $finition->pivot->est_par_defaut ? 'bg-primary-100 text-primary-700 border-2 border-primary-300' : 'bg-aluminum-100 text-aluminum-600' }}">
                                {{ $finition->nom }}
                                @if($finition->code_ral)
                                    <span class="ml-2 w-4 h-4 rounded-full inline-block border border-aluminum-300" 
                                          style="background-color: #{{ $finition->code_ral }}"></span>
                                @endif
                                @if($finition->pivot->est_par_defaut)
                                    <span class="ml-2 text-xs font-medium text-primary-600">(défaut)</span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Colonne latérale --}}
        <div class="lg:col-span-1">
            {{-- Dimensions --}}
            <div class="bg-white rounded-xl shadow-sm border border-aluminum-200 p-6 mb-6">
                <h3 class="text-sm font-semibold text-aluminum-700 uppercase tracking-wider mb-3">Dimensions</h3>
                <div class="space-y-2 text-sm">
                    @if($composant->longueur_barre_mm)
                        <div class="flex justify-between">
                            <span class="text-aluminum-500">Longueur</span>
                            <span class="text-aluminum-700 font-medium">{{ $composant->longueur_barre_mm }} mm</span>
                        </div>
                    @endif
                    @if($composant->section_largeur_mm)
                        <div class="flex justify-between">
                            <span class="text-aluminum-500">Largeur section</span>
                            <span class="text-aluminum-700 font-medium">{{ $composant->section_largeur_mm }} mm</span>
                        </div>
                    @endif
                    @if($composant->section_hauteur_mm)
                        <div class="flex justify-between">
                            <span class="text-aluminum-500">Hauteur section</span>
                            <span class="text-aluminum-700 font-medium">{{ $composant->section_hauteur_mm }} mm</span>
                        </div>
                    @endif
                    @if($composant->epaisseur_paroi_mm)
                        <div class="flex justify-between">
                            <span class="text-aluminum-500">Épaisseur paroi</span>
                            <span class="text-aluminum-700 font-medium">{{ $composant->epaisseur_paroi_mm }} mm</span>
                        </div>
                    @endif
                    @if($composant->poids_lineaire_kg_m)
                        <div class="flex justify-between">
                            <span class="text-aluminum-500">Poids linéaire</span>
                            <span class="text-aluminum-700 font-medium">{{ $composant->poids_lineaire_kg_m }} kg/m</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Propriétés mécaniques --}}
            @if($composant->moment_inertie_x_cm4 || $composant->moment_inertie_y_cm4 || $composant->module_elasticite_x_cm3 || $composant->module_elasticite_y_cm3)
                <div class="bg-white rounded-xl shadow-sm border border-aluminum-200 p-6 mb-6">
                    <h3 class="text-sm font-semibold text-aluminum-700 uppercase tracking-wider mb-3">Propriétés mécaniques</h3>
                    <div class="space-y-2 text-sm">
                        @if($composant->moment_inertie_x_cm4)
                            <div class="flex justify-between">
                                <span class="text-aluminum-500">Inertie X</span>
                                <span class="text-aluminum-700 font-medium">{{ $composant->moment_inertie_x_cm4 }} cm⁴</span>
                            </div>
                        @endif
                        @if($composant->moment_inertie_y_cm4)
                            <div class="flex justify-between">
                                <span class="text-aluminum-500">Inertie Y</span>
                                <span class="text-aluminum-700 font-medium">{{ $composant->moment_inertie_y_cm4 }} cm⁴</span>
                            </div>
                        @endif
                        @if($composant->module_elasticite_x_cm3)
                            <div class="flex justify-between">
                                <span class="text-aluminum-500">Module X</span>
                                <span class="text-aluminum-700 font-medium">{{ $composant->module_elasticite_x_cm3 }} cm³</span>
                            </div>
                        @endif
                        @if($composant->module_elasticite_y_cm3)
                            <div class="flex justify-between">
                                <span class="text-aluminum-500">Module Y</span>
                                <span class="text-aluminum-700 font-medium">{{ $composant->module_elasticite_y_cm3 }} cm³</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Ouvrages utilisant ce composant --}}
            @if(isset($ouvrages) && $ouvrages->count())
                <div class="bg-white rounded-xl shadow-sm border border-aluminum-200 p-6">
                    <h3 class="text-sm font-semibold text-aluminum-700 uppercase tracking-wider mb-3">Utilisé dans</h3>
                    <ul class="space-y-2">
                        @foreach($ouvrages as $ouvrage)
                            <li>
                                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                                   class="text-sm text-primary-600 hover:text-primary-800 transition">
                                    {{ $ouvrage->nom }}
                                </a>
                                <span class="text-xs text-aluminum-400 block">
                                    {{ $ouvrage->gamme?->nom ?? 'Sans gamme' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection