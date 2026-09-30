{{-- resources/views/public/ouvrages/index.blade.php
     Direction "Blueprint" — filtre par gamme retiré (ne reste que catégorie). --}}
@extends('layouts.app')

@section('title', isset($categorieCourante) ? $categorieCourante->nom . ' - AluStock' : 'Tous les ouvrages - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-1.5 text-ink-600">›</span>
    @if(isset($categorieCourante))
        <a href="{{ route('categories.index') }}" class="hover:text-fg transition">Catégories</a>
        <span class="mx-1.5 text-ink-600">›</span>
        <span class="text-ink-200 font-medium">{{ $categorieCourante->nom }}</span>
    @else
        <span class="text-ink-200 font-medium">Tous les ouvrages</span>
    @endif
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-5 pb-3 border-b border-ink-800">
        @if(isset($categorieCourante))
            <h1 class="font-display text-2xl font-bold text-fg flex items-center gap-2">
                @if($categorieCourante->icone)<span>{{ $categorieCourante->icone }}</span>@endif
                {{ $categorieCourante->nom }}
            </h1>
            @if($categorieCourante->description)
                <p class="text-ink-400 text-sm mt-1">{{ $categorieCourante->description }}</p>
            @endif
        @else
            <h1 class="font-display text-2xl font-bold text-fg">Tous les ouvrages</h1>
            <p class="text-ink-400 text-sm mt-1">Catalogue technique complet</p>
        @endif
        <div class="mt-2 font-mono text-[11px] text-ink-500 uppercase tracking-wider">
            {{ $ouvrages->total() }} référence(s)
        </div>
    </div>

    {{-- Filtres actifs --}}
    @if(isset($categorieCourante))
        <div class="mb-4 flex flex-wrap gap-2">
            <a href="{{ route('ouvrages.index') }}"
               class="inline-flex items-center px-3 py-1.5 font-mono text-[11px] bg-ink-900 text-ink-300 border border-ink-700 hover:border-amber-500/60 hover:text-fg transition">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Effacer les filtres
            </a>
            <span class="inline-flex items-center px-3 py-1.5 font-mono text-[11px] bg-amber-500/10 text-amber-400 border border-amber-500/30">
                Catégorie : {{ $categorieCourante->nom }}
            </span>
        </div>
    @endif

    {{-- Grille --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
            @foreach($ouvrages as $ouvrage)
                @include('public.partials.ouvrage-card', ['ouvrage' => $ouvrage])
            @endforeach
        </div>

        <div class="mt-5">{{ $ouvrages->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-ink-900 border border-ink-800">
            <p class="text-ink-400 text-sm">Aucun ouvrage disponible.</p>
        </div>
    @endif
</div>
@endsection