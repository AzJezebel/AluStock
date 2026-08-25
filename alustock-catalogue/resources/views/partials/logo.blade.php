{{-- resources/views/partials/logo.blade.php
     $dark = true  -> variante utilisée sur le header sombre de la home (hero)
     $dark = false -> variante utilisée sur le header clair des pages internes --}}
@php $dark = $dark ?? false; @endphp

<a href="{{ route('home') }}" class="flex items-center space-x-3 shrink-0">
    <div class="w-10 h-10 bg-amber-700 rounded-lg flex items-center justify-center text-white font-bold text-lg">
        A
    </div>
    <div>
        <span class="text-xl font-bold {{ $dark ? 'text-white' : 'text-ink-900' }} tracking-tight">
            Alu<span class="{{ $dark ? 'text-amber-400' : 'text-amber-700' }}">Stock</span>
        </span>
        <span class="block text-[10px] uppercase tracking-widest text-ink-400 font-medium">
            Distribution industrielle
        </span>
    </div>
</a>
