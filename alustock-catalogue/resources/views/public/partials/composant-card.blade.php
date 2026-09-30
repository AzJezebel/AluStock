{{-- resources/views/public/partials/composant-card.blade.php
     Carte composant réutilisable — composants/index, ouvrages/show (composition),
     search/index. Passer $composant, et optionnellement $quantite pour le badge. --}}
@php
    $media = $composant->medias->firstWhere('est_principal', true)
          ?? $composant->medias->sortBy('pivot.ordre')->first();
@endphp
<a href="{{ route('composants.show', $composant->slug) }}"
   class="card-blueprint group relative bg-ink-900 border border-ink-800 hover:border-amber-500/60 transition-colors flex flex-col">

    <span class="tick tick-tl" aria-hidden="true"></span>
    <span class="tick tick-br" aria-hidden="true"></span>

    {{-- Thumbnail — schémas inversés (traits clairs sur fond sombre) pour ne pas
         créer de plaque blanche éblouissante ; survol = couleurs d'origine. --}}
    <div class="aspect-square bg-ink-950 border-b border-ink-800 overflow-hidden flex items-center justify-center relative">
        @if($media)
            <img src="{{ asset('storage/' . $media->chemin_fichier) }}"
                 alt="{{ $media->titre ?? $composant->designation }}"
                 class="schema-img w-full h-full object-contain p-1.5 group-hover:scale-105">
        @else
            <svg class="w-8 h-8 text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        @endif

        @isset($quantite)
            <span class="absolute top-1.5 right-1.5 font-mono text-[10px] font-bold text-ink-950 bg-amber-400 px-1.5 py-0.5 tracking-wider">
                ×{{ $quantite }}
            </span>
        @endisset
    </div>

    <div class="p-2 flex flex-col flex-1">
        <div class="flex items-start justify-between gap-1.5 mb-1.5 min-w-0">
            <span class="font-mono text-[9.5px] font-semibold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.5 tracking-wider truncate max-w-[60%]">
                {{ $composant->reference }}
            </span>
            @if($composant->typeComposant)
                <span class="font-mono text-[9.5px] text-ink-500 uppercase tracking-wider font-semibold truncate max-w-[40%] text-right shrink-0">
                    {{ $composant->typeComposant->nom }}
                </span>
            @endif
        </div>

        <h3 class="text-[13px] font-semibold text-ink-100 leading-snug line-clamp-2 group-hover:text-amber-600 transition-colors">
            {{ $composant->designation }}
        </h3>

        @if($composant->matiere)
            <p class="font-mono text-[10.5px] text-ink-500 mt-1 truncate">
                {{ $composant->matiere }}
            </p>
        @endif

        <div class="mt-auto pt-1.5 border-t border-ink-800 flex items-center justify-end">
            <span class="font-mono text-[9.5px] text-amber-400 font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                VOIR →
            </span>
        </div>
    </div>
</a>