{{-- resources/views/public/composants/index.blade.php
     Direction "Blueprint" — utilise le partial public.partials.composant-card
     (même carte que sur ouvrages/show → composition, et réutilisable partout ailleurs). --}}
@extends('layouts.app')

@section('title', 'Composants - AluStock')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-4 pb-3 border-b border-ink-800 flex items-end justify-between">
        <div>
            <h1 class="font-display text-2xl font-bold text-fg">Tous les composants</h1>
            <p class="font-mono text-[11px] text-ink-500 mt-1 uppercase tracking-wider">
                {{ $composants->total() }} référence(s) documentée(s)
            </p>
        </div>
    </div>

    @if($composants->count())
        {{-- Grille — resserrée --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2">
            @foreach($composants as $composant)
                @include('public.partials.composant-card', ['composant' => $composant])
            @endforeach
        </div>

        <div class="mt-5">{{ $composants->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-ink-900 border border-ink-800">
            <p class="text-ink-400 text-sm">Aucun composant disponible.</p>
        </div>
    @endif
</div>
@endsection