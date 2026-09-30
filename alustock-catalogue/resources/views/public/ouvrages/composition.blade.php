{{-- resources/views/public/ouvrages/composition.blade.php
     Direction "Blueprint" — tableau sombre, mono pour les valeurs numériques. --}}
@extends('layouts.app')

@section('title', 'Composition - ' . $ouvrage->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <a href="{{ route('ouvrages.index') }}" class="hover:text-fg transition">Ouvrages</a>
    <span class="mx-2 text-ink-600">›</span>
    <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" class="hover:text-fg transition">{{ $ouvrage->nom }}</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">Composition</span>
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="relative bg-ink-900 border border-ink-700 p-6 mb-6">
        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl font-bold text-fg">Composition de l'ouvrage</h1>
                <p class="font-mono text-ink-400 text-[12px] mt-1">
                    <span class="font-medium text-ink-200">{{ $ouvrage->nom }}</span>
                    <span class="mx-2 text-ink-700">•</span>
                    Réf. {{ $ouvrage->reference }}
                </p>
            </div>
            <div class="flex items-center gap-4 font-mono text-[12px]">
                <span class="text-ink-500">
                    <span class="font-medium text-ink-200">{{ $composition->count() }}</span> composant(s)
                </span>
                @if(isset($poidsTotal) && $poidsTotal > 0)
                    <span class="text-ink-500">
                        Poids estimé : <span class="font-medium text-ink-200">{{ number_format($poidsTotal, 2) }} kg</span>
                    </span>
                @endif
                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}"
                   class="inline-flex items-center text-amber-400 hover:text-amber-600 transition">
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
        <div class="bg-ink-900 border border-ink-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-ink-800">
                    <thead class="bg-ink-950">
                        <tr>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Composant</th>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Référence</th>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Type</th>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Matière</th>
                            <th class="px-4 py-3 text-center font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-4 py-3 text-center font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Unité</th>
                            <th class="px-4 py-3 text-center font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Longueur coupe</th>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Finition</th>
                            <th class="px-4 py-3 text-left font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-800">
                        @foreach($composition as $composant)
                            <tr class="hover:bg-ink-800/40 transition">
                                {{-- # --}}
                                <td class="px-4 py-3 text-sm text-ink-600 font-mono">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Désignation --}}
                                <td class="px-4 py-3">
                                    <div>
                                        <span class="text-sm font-medium text-ink-100">{{ $composant->designation }}</span>
                                        @if($composant->pivot->commentaire)
                                            <span class="block text-xs text-ink-500 mt-0.5 italic">
                                                {{ $composant->pivot->commentaire }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Référence --}}
                                <td class="px-4 py-3 text-sm text-ink-400 font-mono">
                                    {{ $composant->reference }}
                                </td>

                                {{-- Type --}}
                                <td class="px-4 py-3">
                                    @if($composant->typeComposant)
                                        <span class="font-mono inline-flex items-center px-2.5 py-0.5 text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            {{ $composant->typeComposant->nom }}
                                        </span>
                                    @else
                                        <span class="text-xs text-ink-600">—</span>
                                    @endif
                                </td>

                                {{-- Matière --}}
                                <td class="px-4 py-3 text-sm text-ink-400">
                                    {{ $composant->matiere ?? '—' }}
                                </td>

                                {{-- Quantité --}}
                                <td class="px-4 py-3 text-center text-sm font-semibold font-mono text-ink-100">
                                    {{ $composant->pivot->quantite }}
                                </td>

                                {{-- Unité --}}
                                <td class="px-4 py-3 text-center text-sm text-ink-400">
                                    {{ $composant->pivot->unite }}
                                </td>

                                {{-- Longueur de coupe --}}
                                <td class="px-4 py-3 text-center text-sm text-ink-400 font-mono">
                                    @if($composant->pivot->longueur_coupe_mm)
                                        {{ $composant->pivot->longueur_coupe_mm }} mm
                                    @else
                                        <span class="text-ink-700">—</span>
                                    @endif
                                </td>

                                {{-- Finition par défaut --}}
                                <td class="px-4 py-3 text-sm text-ink-400">
                                    @if($composant->finitions->first())
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-3 h-3 border border-ink-600"
                                                  style="background-color: {{ $composant->finitions->first()->code_ral ? '#' . $composant->finitions->first()->code_ral : '#4D4D4D' }}"></span>
                                            {{ $composant->finitions->first()->nom }}
                                        </span>
                                    @else
                                        <span class="text-ink-700">—</span>
                                    @endif
                                </td>

                                {{-- Action --}}
                                <td class="px-4 py-3">
                                    <a href="{{ route('composants.show', $composant->slug) }}"
                                       class="text-sm font-medium text-amber-400 hover:text-amber-600 transition">
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
                    <tfoot class="bg-ink-950 border-t border-ink-800">
                        <tr>
                            <td colspan="10" class="px-4 py-3">
                                <div class="flex flex-wrap items-center justify-between gap-2 font-mono text-[12px] text-ink-400">
                                    <span>
                                        <span class="font-medium text-ink-200">{{ $composition->count() }}</span> composant(s) au total
                                    </span>
                                    @if(isset($poidsTotal) && $poidsTotal > 0)
                                        <span>
                                            Poids total estimé : <span class="font-bold text-ink-100">{{ number_format($poidsTotal, 2) }} kg</span>
                                        </span>
                                    @endif
                                    <span class="text-ink-600 text-[11px]">
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
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Légende --}}
            <div class="bg-ink-900 border border-ink-800 p-4">
                <h3 class="font-mono text-[10px] font-semibold text-ink-500 uppercase tracking-widest mb-2">Légende</h3>
                <ul class="space-y-1.5 text-sm text-ink-400">
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-amber-500/20 border border-amber-500/40"></span>
                        Type de composant
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-ink-700 border border-ink-600"></span>
                        Finition par défaut
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-ink-700">—</span>
                        Non renseigné
                    </li>
                </ul>
            </div>

            {{-- Informations --}}
            <div class="bg-ink-900 border border-ink-800 p-4">
                <h3 class="font-mono text-[10px] font-semibold text-ink-500 uppercase tracking-widest mb-2">Informations</h3>
                <ul class="space-y-1.5 text-sm text-ink-400">
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
                    class="inline-flex items-center px-4 py-2 bg-ink-800 text-ink-200 border border-ink-700 hover:bg-ink-700 hover:text-fg transition text-sm font-medium font-mono">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Imprimer la composition
            </button>
        </div>
    @else
        <div class="text-center py-12 bg-ink-900 border border-ink-800">
            <div class="text-ink-500">
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