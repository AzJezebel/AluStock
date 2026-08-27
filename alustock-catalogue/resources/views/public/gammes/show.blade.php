{{-- resources/views/public/gammes/show.blade.php --}}
@extends('layouts.app')

@section('title', $gamme->nom . ' - AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-700">Accueil</a>
    <span class="mx-2">›</span>
    <a href="{{ route('gammes.index') }}" class="hover:text-ink-700">Gammes</a>
    <span class="mx-2">›</span>
    <span class="text-ink-700 font-medium">{{ $gamme->nom }}</span>
@endsection

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-ink-900">{{ $gamme->nom }}</h1>
        @if($gamme->description)
            <p class="text-ink-500 text-sm mt-1">{{ $gamme->description }}</p>
        @endif
        <div class="mt-2 text-sm text-ink-400">
            <span class="font-medium text-ink-700">{{ $ouvrages->total() }}</span> ouvrage(s)
        </div>
    </div>

    {{-- Ouvrages de la gamme --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ouvrages as $ouvrage)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300">
                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <h3 class="text-base font-semibold text-ink-900 group-hover:text-amber-700 transition flex-1">
                                <a href="{{ route('ouvrages.show', $ouvrage->slug) }}">{{ $ouvrage->nom }}</a>
                            </h3>
                            <span class="text-xs text-ink-400 ml-2">{{ $ouvrage->reference }}</span>
                        </div>
                        @if($ouvrage->description_courte)
                            <p class="text-sm text-ink-500 mt-2 line-clamp-2">{{ $ouvrage->description_courte }}</p>
                        @endif
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-ink-100">
                            <span class="text-xs text-ink-400">{{ $ouvrage->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                            <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                               class="inline-flex items-center text-sm font-medium text-amber-700 hover:text-amber-800 transition">
                                Voir détails
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $ouvrages->links() }}</div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-ink-200">
            <p class="text-ink-500">Aucun ouvrage dans cette gamme.</p>
        </div>
    @endif
</div>
@endsection