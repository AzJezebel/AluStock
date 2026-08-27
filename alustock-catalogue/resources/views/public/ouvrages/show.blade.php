{{-- resources/views/public/ouvrages/show.blade.php --}}
@extends('layouts.app')

@section('title', $ouvrage->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-2">›</span>
    <a href="{{ route('ouvrages.index') }}" class="hover:text-ink-700">Ouvrages</a>
    <span class="mx-2">›</span>
    @if($ouvrage->categorie)
        <a href="{{ route('ouvrages.index', ['categorie' => $ouvrage->categorie->slug]) }}" class="hover:text-ink-700">{{ $ouvrage->categorie->nom }}</a>
        <span class="mx-2">›</span>
    @endif
    <span class="text-ink-700 font-medium">{{ $ouvrage->nom }}</span>
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-ink-900">{{ $ouvrage->nom }}</h1>
                <p class="text-sm text-ink-500 mt-1">Référence : {{ $ouvrage->reference }}</p>
                @if($ouvrage->description_courte)
                    <p class="text-ink-600 mt-2">{{ $ouvrage->description_courte }}</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                @if($ouvrage->gamme)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">{{ $ouvrage->gamme->nom }}</span>
                @endif
                @if($ouvrage->categorie)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-ink-100 text-ink-600">{{ $ouvrage->categorie->nom }}</span>
                @endif
                @if($ouvrage->performance_thermique)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">{{ $ouvrage->performance_thermique }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Colonne principale --}}
        <div class="lg:col-span-2">
            @if($ouvrage->description_technique)
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-ink-900 mb-2">Description technique</h2>
                    <div class="prose prose-sm text-ink-600 max-w-none">{{ $ouvrage->description_technique }}</div>
                </div>
            @endif

            @if($ouvrage->caracteristiques->count())
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
                    <h2 class="text-lg font-semibold text-ink-900 mb-4">Caractéristiques techniques</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($ouvrage->caracteristiques as $carac)
                            <div class="flex items-center justify-between p-3 bg-ink-50 rounded-lg">
                                <span class="text-sm text-ink-600">{{ $carac->cle }}</span>
                                <span class="text-sm font-medium text-ink-900">
                                    {{ $carac->valeur }}
                                    @if($carac->unite)<span class="text-xs text-ink-400">{{ $carac->unite }}</span>@endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Composition --}}
            @if($ouvrage->composants->count())
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-ink-900">Composition</h2>
                        <a href="{{ route('ouvrages.composition', $ouvrage->slug) }}" 
                           class="text-sm font-medium text-amber-700 hover:text-amber-800 transition">
                            Voir la composition détaillée
                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-ink-200">
                            <thead class="bg-ink-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">#</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Composant</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Réf.</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-ink-500 uppercase tracking-wider">Qté</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ink-100">
                                @foreach($ouvrage->composants as $composant)
                                    <tr class="hover:bg-ink-50">
                                        <td class="px-4 py-2 text-sm text-ink-500">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 text-sm text-ink-900">
                                            <a href="{{ route('composants.show', $composant->slug) }}" class="hover:text-amber-700 transition">
                                                {{ $composant->designation }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-ink-500">{{ $composant->reference }}</td>
                                        <td class="px-4 py-2 text-sm text-ink-700 text-right font-medium">{{ $composant->pivot->quantite }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Colonne latérale --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
                <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('ouvrages.composition', $ouvrage->slug) }}" 
                       class="flex items-center w-full px-4 py-2 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Voir la composition
                    </a>
                    <a href="#" target="_blank"
                       class="flex items-center w-full px-4 py-2 bg-ink-100 text-ink-700 rounded-lg hover:bg-ink-200 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Version imprimable
                    </a>
                    <a href="#" 
                       class="flex items-center w-full px-4 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger PDF
                    </a>
                </div>
            </div>

            @if($ouvrage->largeur_min_mm || $ouvrage->largeur_max_mm || $ouvrage->hauteur_min_mm || $ouvrage->hauteur_max_mm)
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
                    <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-3">Dimensions</h3>
                    <div class="space-y-1 text-sm">
                        @if($ouvrage->largeur_min_mm || $ouvrage->largeur_max_mm)
                            <div class="flex justify-between">
                                <span class="text-ink-500">Largeur</span>
                                <span class="text-ink-700 font-medium">
                                    @if($ouvrage->largeur_min_mm && $ouvrage->largeur_max_mm)
                                        {{ $ouvrage->largeur_min_mm }} - {{ $ouvrage->largeur_max_mm }} mm
                                    @elseif($ouvrage->largeur_min_mm)≥ {{ $ouvrage->largeur_min_mm }} mm
                                    @elseif($ouvrage->largeur_max_mm)≤ {{ $ouvrage->largeur_max_mm }} mm
                                    @endif
                                </span>
                            </div>
                        @endif
                        @if($ouvrage->hauteur_min_mm || $ouvrage->hauteur_max_mm)
                            <div class="flex justify-between">
                                <span class="text-ink-500">Hauteur</span>
                                <span class="text-ink-700 font-medium">
                                    @if($ouvrage->hauteur_min_mm && $ouvrage->hauteur_max_mm)
                                        {{ $ouvrage->hauteur_min_mm }} - {{ $ouvrage->hauteur_max_mm }} mm
                                    @elseif($ouvrage->hauteur_min_mm)≥ {{ $ouvrage->hauteur_min_mm }} mm
                                    @elseif($ouvrage->hauteur_max_mm)≤ {{ $ouvrage->hauteur_max_mm }} mm
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($ouvrage->documents->count())
                <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6">
                    <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-3">Documents</h3>
                    <ul class="space-y-2">
                        @foreach($ouvrage->documents as $document)
                            <li>
                                <a href="{{ route('documents.download', $document->id) }}" 
                                   class="flex items-center text-sm text-amber-700 hover:text-amber-800 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $document->titre }}
                                    @if($document->taille_octets)<span class="text-xs text-ink-400 ml-2">({{ number_format($document->taille_octets / 1024, 1) }} KB)</span>@endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection