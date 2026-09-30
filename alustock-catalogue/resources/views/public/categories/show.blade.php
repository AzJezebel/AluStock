{{-- resources/views/public/categories/show.blade.php --}}
@extends('layouts.app')

@section('title', $categorie->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <a href="{{ route('categories.index') }}" class="hover:text-fg transition">Catégories</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">{{ $categorie->nom }}</span>
@endsection

@section('content')
<div>
    <div class="mb-6 pb-3 border-b border-ink-800">
        <h1 class="font-display text-2xl font-bold text-fg flex items-center gap-3">
            @if($categorie->icone)<span>{{ $categorie->icone }}</span>@endif
            {{ $categorie->nom }}
        </h1>
        @if($categorie->description)
            <p class="text-ink-400 text-sm mt-1">{{ $categorie->description }}</p>
        @endif
        <div class="mt-2 font-mono text-[11px] text-ink-500 uppercase tracking-wider">
            {{ $ouvrages->total() }} ouvrage(s)
        </div>
    </div>

    @if($ouvrages->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ouvrages as $ouvrage)
                @include('public.partials.ouvrage-card', ['ouvrage' => $ouvrage])
            @endforeach
        </div>
        <div class="mt-6">{{ $ouvrages->links() }}</div>
    @else
        <div class="text-center py-12 bg-ink-900 border border-ink-800">
            <p class="text-ink-400 text-sm">Aucun ouvrage dans cette catégorie.</p>
        </div>
    @endif
</div>
@endsection