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
@php
    /*
     * Poids estimé d'une ligne = Kg/m (composant) × longueur en mètres × quantité.
     * La longueur vient, dans l'ordre :
     *   1. de la quantité elle-même si l'unité est le mètre (m / ml) ;
     *   2. de la longueur de barre du composant si l'unité est "barre".
     * Sinon la ligne n'est pas calculable (vis, joints, accessoires…) et est exclue du total.
     */
    $poidsLigne = function ($c) {
        $kgm   = (float) ($c->poids_lineaire_kg_m ?? 0);
        $qte   = (float) ($c->pivot->quantite ?? 0);
        $barre = (float) ($c->longueur_barre_mm ?? 0);
        $unite = \Illuminate\Support\Str::of((string) ($c->pivot->unite ?? ''))->ascii()->lower()->trim()->toString();

        if ($kgm <= 0) {
            return ['kg' => null, 'raison' => 'Kg/m non renseigné', 'detail' => null, 'formule' => null];
        }
        if (in_array($unite, ['m', 'ml', 'metre', 'metres'], true)) {
            return ['kg' => $kgm * $qte, 'raison' => null,
                    'formule' => rtrim(rtrim(number_format($kgm, 3, '.', ''), '0'), '.') . ' kg/m × ' . rtrim(rtrim(number_format($qte, 2, '.', ''), '0'), '.') . ' m'];
        }
        if (in_array($unite, ['barre', 'barres'], true) && $barre > 0) {
            $m = $barre / 1000;
            return ['kg' => $kgm * $m * $qte, 'raison' => null,
                    'formule' => rtrim(rtrim(number_format($kgm, 3, '.', ''), '0'), '.') . ' kg/m × ' . rtrim(rtrim(number_format($m, 3, '.', ''), '0'), '.') . ' m (barre) × ' . rtrim(rtrim(number_format($qte, 2, '.', ''), '0'), '.')];
        }
        return ['kg' => null, 'raison' => 'Longueur inconnue', 'detail' => '(ni mètres, ni barre)', 'formule' => null];
    };

    $poids = [];
    foreach ($composition as $c) {
        $poids[$c->id] = $poidsLigne($c);
    }
    $poidsCalcule = collect($poids)->sum(fn ($p) => $p['kg'] ?? 0);
    $nbSansPoids  = collect($poids)->filter(fn ($p) => $p['kg'] === null)->count();
@endphp
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
                @if($poidsCalcule > 0)
                    <span class="text-ink-500">
                        Poids estimé : <span class="font-medium text-ink-200">{{ number_format($poidsCalcule, 2, ',', ' ') }} kg</span>
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
                        <tr class="font-mono text-[10px] font-medium text-ink-500 uppercase tracking-wider">
                            <th class="pl-3 pr-1 py-2 text-left">#</th>
                            <th class="px-2.5 py-2 text-left">Composant</th>
                            <th class="px-2.5 py-2 text-left">Matière</th>
                            <th class="px-2.5 py-2 text-center">Qté</th>
                            <th class="px-2.5 py-2 text-left">Finition</th>
                            <th class="pl-2.5 pr-3 py-2 text-right">Poids est.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-800">
                        @foreach($composition as $composant)
                            @php
                                $p = $poids[$composant->id];
                                $fin = $composant->finitions->first();
                            @endphp
                            <tr class="hover:bg-ink-800/40 transition align-top">
                                {{-- # --}}
                                <td class="pl-3 pr-1 py-2 text-xs text-ink-600 font-mono">{{ $loop->iteration }}</td>

                                {{-- Composant : désignation (lien) + référence + type --}}
                                <td class="px-2.5 py-2">
                                    <a href="{{ route('composants.show', $composant->slug) }}"
                                       class="text-[13px] font-medium text-ink-100 hover:text-amber-600 transition leading-snug">
                                        {{ $composant->designation }}
                                    </a>
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 mt-0.5 font-mono text-[11px] text-ink-500">
                                        <span>{{ $composant->reference }}</span>
                                        @if($composant->typeComposant)
                                            <span class="px-1.5 text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                {{ $composant->typeComposant->nom }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($composant->pivot->commentaire)
                                        <span class="block text-[11px] text-ink-500 mt-0.5 italic">{{ $composant->pivot->commentaire }}</span>
                                    @endif
                                </td>

                                {{-- Matière --}}
                                <td class="px-2.5 py-2 text-xs text-ink-400">{{ $composant->matiere ?? '—' }}</td>

                                {{-- Quantité + unité --}}
                                <td class="px-2.5 py-2 text-center font-mono whitespace-nowrap">
                                    <span class="text-sm font-semibold text-ink-100">{{ $composant->pivot->quantite }}</span>
                                    @if($composant->pivot->unite)
                                        <span class="text-[11px] text-ink-500">{{ $composant->pivot->unite }}</span>
                                    @endif
                                </td>


                                {{-- Finition par défaut --}}
                                <td class="px-2.5 py-2 text-xs text-ink-400">
                                    @if($fin)
                                        <span class="inline-flex items-center gap-1.5">
                                            <span class="w-2.5 h-2.5 shrink-0 border border-ink-600"
                                                  style="background-color: {{ $fin->code_ral ? '#' . $fin->code_ral : '#4D4D4D' }}"></span>
                                            {{ $fin->nom }}
                                        </span>
                                    @else
                                        <span class="text-ink-700">—</span>
                                    @endif
                                </td>

                                {{-- Poids estimé de la ligne (le texte secondaire passe à la ligne) --}}
                                <td class="pl-2.5 pr-3 py-2 text-right font-mono">
                                    @if($p['kg'] !== null)
                                        <span class="text-sm font-semibold text-ink-100 whitespace-nowrap">{{ number_format($p['kg'], 2, ',', ' ') }} kg</span>
                                        <span class="block ml-auto max-w-[11rem] text-[10px] leading-tight text-ink-600">{{ $p['formule'] }}</span>
                                    @else
                                        <span class="text-ink-700" title="{{ $p['raison'] }} {{ $p['detail'] ?? '' }}">—</span>
                                        <span class="block ml-auto max-w-[11rem] text-[10px] leading-tight text-ink-600">
                                            {{ $p['raison'] }}
                                            @if(!empty($p['detail']))<br>{{ $p['detail'] }}@endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    {{-- Pied de tableau avec récapitulatif --}}
                    <tfoot class="bg-ink-950 border-t border-ink-800">
                        <tr>
                            <td colspan="6" class="px-3 py-2.5">
                                <div class="flex flex-wrap items-center justify-between gap-2 font-mono text-[12px] text-ink-400">
                                    <span>
                                        <span class="font-medium text-ink-200">{{ $composition->count() }}</span> composant(s) au total
                                    </span>
                                    @if($poidsCalcule > 0)
                                        <span>
                                            Poids total estimé : <span class="font-bold text-ink-100">{{ number_format($poidsCalcule, 2, ',', ' ') }} kg</span>
                                            @if($nbSansPoids > 0)
                                                <span class="text-amber-600 text-[11px] ml-1">({{ $nbSansPoids }} ligne(s) non comptée(s))</span>
                                            @endif
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
                    @if($poidsCalcule > 0)
                        <li>• Poids estimé = Kg/m × longueur (m) × quantité. Les lignes sans Kg/m ou sans longueur (visserie, joints…) ne sont pas comptées.</li>
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