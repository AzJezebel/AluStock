{{-- resources/views/public/partials/logo.blade.php
     Direction "Blueprint" — une seule variante, pensée pour le bandeau sombre unifié.
     (le paramètre $dark de l'ancienne version n'est plus nécessaire : le header
      est toujours sombre dans cette direction) --}}

<a href="{{ route('home') }}" class="flex items-center space-x-3 shrink-0 group">
    <div class="relative w-9 h-9 border border-amber-500/50 bg-ink-950 flex items-center justify-center">
        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>
        <span class="font-mono text-[11px] font-bold text-amber-400 tracking-tight">AS</span>
    </div>
    <div class="leading-none">
        <span class="font-display text-lg font-bold tracking-tight text-fg">
            ALU<span class="text-amber-400">STOCK</span>
        </span>
        <span class="block font-mono text-[9px] uppercase tracking-[0.25em] text-ink-400 mt-1">
            Réf. catalogue technique
        </span>
    </div>
</a>