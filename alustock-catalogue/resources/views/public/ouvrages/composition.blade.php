{{-- resources/views/public/ouvrages/composition.blade.php --}}
@extends('layouts.app')

@section('title', 'Composition - ' . $ouvrage->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-2">›</span>
    <a href="{{ route('ouvrages.index') }}" class="hover:text-ink-700">Ouvrages</a>
    <span class="mx-2">›</span>
    <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" class="hover:text-ink-700">{{ $ouvrage->nom }}</a>
    <span class="mx-2">›</span>
    <span class="text-ink-700 font-medium">Composition</span>
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-ink-900">Composition de l'ouvrage</h1>
                <p class="text-ink-500 text-sm mt-1">
                    <span class="font-medium text-ink-700">{{ $ouvrage->nom }}</span>
                    <span class="mx-2">•</span>
                    Réf. {{ $ouvrage->reference }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-4 text-sm">
                <span class="text-ink-400">
                    <span class="font-medium text-ink-700">{{ $composition->count() }}</span> composant(s)
                </span>
                @if(isset($poidsTotal) && $poidsTotal > 0)
                    <span class="text-ink-400">
                        Poids estimé : <span class="font-medium text-ink-700">{{ number_format($poidsTotal, 2) }} kg</span>
                    </span>
                @endif
                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                   class="inline-flex items-center text-sm font-medium text-amber-700 hover:text-amber-800 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    {{-- Tableau de composition --}}
    @if($composition->count())
        <div class="bg-white rounded-xl shadow-sm border border-ink-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-ink-200">
                    <thead class="bg-ink-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Composant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Référence</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Matière</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-ink-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-ink-500 uppercase tracking-wider">Unité</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-ink-500 uppercase tracking-wider">Longueur coupe</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Finition</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-ink-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @foreach($composition as $composant)
                            <tr class="hover:bg-ink-50 transition">
                                {{-- # --}}
                                <td class="px-4 py-3 text-sm text-ink-400">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Désignation --}}
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="text-sm font-medium text-ink-900">{{ $composant->designation }}</span>
                                        @if($composant->pivot->commentaire)
                                            <span class="block text-xs text-ink-400 mt-0.5">
                                                <span class="italic">{{ $composant->pivot->commentaire }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Référence --}}
                                <td class="px-4 py-3 text-sm text-ink-500">
                                    {{ $composant->reference }}
                                </td>

                                {{-- Type --}}
                                <td class="px-4 py-3">
                                    @if($composant->typeComposant)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                                            {{ $composant->typeComposant->nom }}
                                        </span>
                                    @else
                                        <span class="text-xs text-ink-400">—</span>
                                    @endif
                                </td>

                                {{-- Matière --}}
                                <td class="px-4 py-3 text-sm text-ink-500">
                                    {{ $composant->matiere ?? '—' }}
                                </td>

                                {{-- Quantité --}}
                                <td class="px-4 py-3 text-center text-sm font-semibold text-ink-900">
                                    {{ $composant->pivot->quantite }}
                                </td>

                                {{-- Unité --}}
                                <td class="px-4 py-3 text-center text-sm text-ink-500">
                                    {{ $composant->pivot->unite }}
                                </td>

                                {{-- Longueur de coupe --}}
                                <td class="px-4 py-3 text-center text-sm text-ink-500">
                                    @if($composant->pivot->longueur_coupe_mm)
                                        {{ $composant->pivot->longueur_coupe_mm }} mm
                                    @else
                                        <span class="text-ink-300">—</span>
                                    @endif
                                </td>

                                {{-- Finition par défaut --}}
                                <td class="px-4 py-3 text-sm text-ink-500">
                                    @if($composant->finitions->first())
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-3 h-3 rounded-full border border-ink-300" 
                                                  style="background-color: {{ $composant->finitions->first()->code_ral ? '#' . $composant->finitions->first()->code_ral : '#e5e7eb' }}"></span>
                                            {{ $composant->finitions->first()->nom }}
                                        </span>
                                    @else
                                        <span class="text-ink-300">—</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-4 py-3">
                                    <a href="{{ route('composants.show', $composant->slug) }}" 
                                       class="text-sm font-medium text-amber-700 hover:text-amber-800 transition">
                                        Voir
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- Pied de tableau avec récapitulatif --}}
                    <tfoot class="bg-ink-50 border-t border-ink-200">
                        <tr>
                            <td colspan="10" class="px-4 py-3 text-sm text-ink-600">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span>
                                        <span class="font-medium">{{ $composition->count() }}</span> composant(s) au total
                                    </span>
                                    @if(isset($poidsTotal) && $poidsTotal > 0)
                                        <span>
                                            Poids total estimé : <span class="font-bold text-ink-900">{{ number_format($poidsTotal, 2) }} kg</span>
                                        </span>
                                    @endif
                                    <span class="text-ink-400 text-xs">
                                        Dernière mise à jour : {{ $ouvrage->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Légende et informations complémentaires --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Légende --}}
            <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-4">
                <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-2">Légende</h3>
                <ul class="space-y-1 text-sm text-ink-500">
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-100 border border-amber-300"></span>
                        Type de composant
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-ink-200 border border-ink-300"></span>
                        Finition par défaut
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-ink-300">—</span>
                        Non renseigné
                    </li>
                </ul>
            </div>

            {{-- Informations --}}
            <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-4">
                <h3 class="text-sm font-semibold text-ink-700 uppercase tracking-wider mb-2">Informations</h3>
                <ul class="space-y-1 text-sm text-ink-500">
                    <li>• Les quantités sont données par ouvrage fini.</li>
                    <li>• Les longueurs de coupe sont indiquées en millimètres (mm).</li>
                    @if(isset($poidsTotal) && $poidsTotal > 0)
                        <li>• Le poids total est une estimation basée sur les données fournies.</li>
                    @endif
                    <li>• Cliquez sur "Voir" pour accéder à la fiche technique du composant.</li>
                </ul>
            </div>
        </div>

        {{-- Bouton d'impression --}}
        <div class="mt-6 flex justify-end">
            <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-ink-100 text-ink-700 rounded-lg hover:bg-ink-200 transition text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimer la composition
            </button>
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-ink-200">
            <div class="text-ink-400">
                <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-sm font-medium">Aucun composant associé à cet ouvrage</p>
                <p class="text-xs mt-1">La composition de cet ouvrage n'a pas encore été définie.</p>
            </div>
        </div>
    @endif
</div>
@endsection