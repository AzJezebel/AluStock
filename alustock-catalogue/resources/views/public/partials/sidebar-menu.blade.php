{{-- resources/views/public/partials/sidebar-menu.blade.php
     Direction "Blueprint" — rangées numérotées façon nomenclature de plan technique
     (01 — CATÉGORIE), fond sombre, séparateurs fins, accent cyan sur l'état actif. --}}

@php
    $sidebarCategories = \App\Models\Categorie::withCount('ouvrages')->orderBy('nom')->get();

    $currentRoute = Route::currentRouteName();
    $currentParams = Route::current()->parameters();

    $activeCategorie = $currentParams['categorie'] ?? $currentParams['categorie_slug'] ?? null;
@endphp

{{-- Section : Catégories --}}
<div class="mb-5">
    <div class="font-mono text-[10px] font-semibold text-ink-500 uppercase tracking-[0.2em] px-2.5 py-2 border-b border-ink-800 mb-1">
        01 · Catégories
    </div>
    <ul>
        @foreach($sidebarCategories as $i => $categorie)
            @php
                $isActive = $activeCategorie == $categorie->slug;
                $hasOuvrages = $categorie->ouvrages_count > 0;
            @endphp
            <li>
                <a href="{{ $hasOuvrages ? route('ouvrages.index', ['categorie' => $categorie->slug]) : '#' }}"
                   class="group flex items-center justify-between px-2.5 py-1.5 text-sm border-l-2 transition-colors
                          {{ $isActive ? 'border-amber-400 bg-ink-800/60 text-amber-600 font-semibold' : 'border-transparent text-ink-300 hover:border-ink-600 hover:bg-ink-800/40 hover:text-fg' }}
                          {{ !$hasOuvrages ? 'opacity-30 cursor-not-allowed' : '' }}"
                   @if(!$hasOuvrages) onclick="return false;" @endif>
                    <span class="flex items-center gap-2.5 min-w-0">
                        <span class="font-mono text-[10px] {{ $isActive ? 'text-amber-400' : 'text-ink-600 group-hover:text-ink-400' }}">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <span class="truncate">{{ $categorie->nom }}</span>
                    </span>
                    <span class="font-mono text-[10px] text-ink-600">{{ $categorie->ouvrages_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Section : Liens rapides --}}
<div>
    <div class="font-mono text-[10px] font-semibold text-ink-500 uppercase tracking-[0.2em] px-2.5 py-2 border-b border-ink-800 mb-1">
        02 · Index
    </div>
    <ul>
        <li>
            <a href="{{ route('ouvrages.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-1.5 text-sm border-l-2 transition-colors
                      {{ $currentRoute === 'ouvrages.index' && !request()->has('categorie') && !request()->has('gamme') ? 'border-amber-400 bg-ink-800/60 text-amber-600 font-semibold' : 'border-transparent text-ink-300 hover:border-ink-600 hover:bg-ink-800/40 hover:text-fg' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Tous les ouvrages
            </a>
        </li>
        <li>
            <a href="{{ route('composants.index') }}"
               class="flex items-center gap-2.5 px-2.5 py-1.5 text-sm border-l-2 transition-colors
                      {{ $currentRoute === 'composants.index' ? 'border-amber-400 bg-ink-800/60 text-amber-600 font-semibold' : 'border-transparent text-ink-300 hover:border-ink-600 hover:bg-ink-800/40 hover:text-fg' }}">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Tous les composants
            </a>
        </li>
    </ul>
</div>