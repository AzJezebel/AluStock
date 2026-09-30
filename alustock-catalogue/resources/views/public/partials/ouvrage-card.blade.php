{{-- resources/views/public/partials/ouvrage-card.blade.php
     Carte ouvrage réutilisable — ouvrages/index, categories/show, search/index.
     Passer $ouvrage. Pas de badge gamme (retiré du modèle de présentation). --}}
@php
    $media = $ouvrage->medias->firstWhere('est_principal', true)
          ?? $ouvrage->medias->sortBy('pivot.ordre')->first();
@endphp
<a href="{{ route('ouvrages.show', $ouvrage->slug) }}"
   class="card-blueprint group relative bg-ink-900 border border-ink-800 hover:border-amber-500/60 transition-colors flex flex-col">

    <span class="tick tick-tl" aria-hidden="true"></span>
    <span class="tick tick-br" aria-hidden="true"></span>

    <div class="aspect-square bg-ink-950 border-b border-ink-800 overflow-hidden flex items-center justify-center">
        @if($media)
            <img src="{{ asset('storage/' . $media->chemin_fichier) }}"
                 alt="{{ $media->titre ?? $ouvrage->nom }}"
                 class="schema-img w-full h-full object-contain p-2 group-hover:scale-105">
        @else
            <svg class="w-10 h-10 text-ink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        @endif
    </div>

    <div class="p-3 flex flex-col flex-1">
        <div class="flex items-start justify-between gap-2 mb-1.5 min-w-0">
            <span class="font-mono text-[10px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-1.5 py-0.5 tracking-wider truncate max-w-[60%]">
                {{ $ouvrage->reference }}
            </span>
            @if($ouvrage->categorie)
                <span class="font-mono text-[10px] text-ink-500 uppercase tracking-wider font-semibold truncate max-w-[40%] text-right shrink-0">
                    {{ $ouvrage->categorie->nom }}
                </span>
            @endif
        </div>

        <h3 class="text-sm font-semibold text-ink-100 leading-snug line-clamp-2 group-hover:text-amber-600 transition-colors">
            {{ $ouvrage->nom }}
        </h3>

        @if($ouvrage->description_courte)
            <p class="text-xs text-ink-400 line-clamp-2 mt-1.5 leading-snug">
                {{ $ouvrage->description_courte }}
            </p>
        @endif

        <div class="mt-auto pt-2 border-t border-ink-800 flex items-center justify-end">
            <span class="font-mono text-[10px] text-amber-400 font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                VOIR →
            </span>
        </div>
    </div>
</a>