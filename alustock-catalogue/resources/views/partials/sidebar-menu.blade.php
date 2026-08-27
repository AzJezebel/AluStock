{{-- resources/views/partials/sidebar-menu.blade.php --}}

@php
    $sidebarCategories = \App\Models\Categorie::withCount('ouvrages')->orderBy('nom')->get();
    $sidebarGammes = \App\Models\Gamme::withCount('ouvrages')->orderBy('ordre_affichage')->get();
    
    $currentRoute = Route::currentRouteName();
    $currentParams = Route::current()->parameters();
    
    $activeCategorie = $currentParams['categorie'] ?? $currentParams['categorie_slug'] ?? null;
    $activeGamme = $currentParams['gamme'] ?? $currentParams['gamme_slug'] ?? null;
@endphp

{{-- Section : Catégories --}}
<div class="mb-4">
    <div class="text-xs font-semibold text-ink-400 uppercase tracking-wider px-3 py-2">
        Catégories
    </div>
    <ul class="space-y-0.5">
        @foreach($sidebarCategories as $categorie)
            @php
                $isActive = $activeCategorie == $categorie->slug;
                $hasOuvrages = $categorie->ouvrages_count > 0;
            @endphp
            <li>
                <a href="{{ $hasOuvrages ? route('ouvrages.index', ['categorie' => $categorie->slug]) : '#' }}"
                   class="flex items-center justify-between px-3 py-1.5 rounded-lg text-sm transition
                          {{ $isActive ? 'bg-amber-50 text-amber-700 font-medium' : 'text-ink-600 hover:bg-ink-50 hover:text-ink-900' }}
                          {{ !$hasOuvrages ? 'opacity-40 cursor-not-allowed' : '' }}"
                   @if(!$hasOuvrages) onclick="return false;" @endif>
                    <span>{{ $categorie->nom }}</span>
                    <span class="text-xs text-ink-400">{{ $categorie->ouvrages_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Séparateur --}}
<div class="border-t border-ink-200 my-3"></div>

{{-- Section : Gammes --}}
<div>
    <div class="text-xs font-semibold text-ink-400 uppercase tracking-wider px-3 py-2">
        Gammes
    </div>
    <ul class="space-y-0.5">
        @foreach($sidebarGammes as $gamme)
            @php
                $isActive = $activeGamme == $gamme->slug;
                $hasOuvrages = $gamme->ouvrages_count > 0;
            @endphp
            <li>
                <a href="{{ $hasOuvrages ? route('ouvrages.index', ['gamme' => $gamme->slug]) : '#' }}"
                   class="flex items-center justify-between px-3 py-1.5 rounded-lg text-sm transition
                          {{ $isActive ? 'bg-amber-50 text-amber-700 font-medium' : 'text-ink-600 hover:bg-ink-50 hover:text-ink-900' }}
                          {{ !$hasOuvrages ? 'opacity-40 cursor-not-allowed' : '' }}"
                   @if(!$hasOuvrages) onclick="return false;" @endif>
                    <span>{{ $gamme->nom }}</span>
                    <span class="text-xs text-ink-400">{{ $gamme->ouvrages_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- Section : Liens rapides --}}
<div class="border-t border-ink-200 my-3"></div>

<div>
    <ul class="space-y-0.5">
        <li>
            <a href="{{ route('ouvrages.index') }}" 
               class="flex items-center px-3 py-1.5 rounded-lg text-sm transition
                      {{ $currentRoute === 'ouvrages.index' && !request()->has('categorie') && !request()->has('gamme') ? 'bg-amber-50 text-amber-700 font-medium' : 'text-ink-600 hover:bg-ink-50 hover:text-ink-900' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Tous les ouvrages
            </a>
        </li>
        <li>
            <a href="{{ route('composants.index') }}" 
               class="flex items-center px-3 py-1.5 rounded-lg text-sm transition
                      {{ $currentRoute === 'composants.index' ? 'bg-amber-50 text-amber-700 font-medium' : 'text-ink-600 hover:bg-ink-50 hover:text-ink-900' }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Tous les composants
            </a>
        </li>
    </ul>
</div>