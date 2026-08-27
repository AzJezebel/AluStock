{{-- resources/views/public/composants/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Composants - AluStock')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-ink-900">Tous les composants</h1>
        <p class="text-ink-500 text-sm mt-1">Découvrez l'ensemble de nos composants techniques.</p>
    </div>

    @if($composants->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($composants as $composant)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300">
                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <h3 class="text-base font-semibold text-ink-900 group-hover:text-amber-700 transition flex-1">
                                <a href="{{ route('composants.show', $composant->slug) }}">{{ $composant->designation }}</a>
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ml-2">
                                {{ $composant->reference }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-3">
                            @if($composant->typeComposant)<span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-ink-100 text-ink-600">{{ $composant->typeComposant->nom }}</span>@endif
                            @if($composant->gamme)<span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-ink-100 text-ink-600">{{ $composant->gamme->nom }}</span>@endif
                            @if($composant->matiere)<span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-ink-100 text-ink-600">{{ $composant->matiere }}</span>@endif
                        </div>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-ink-100">
                            <span class="text-xs text-ink-400">{{ $composant->created_at?->format('d/m/Y') ?? 'N/A' }}</span>
                            <a href="{{ route('composants.show', $composant->slug) }}" 
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
        <div class="mt-6">{{ $composants->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-ink-200">
            <p class="text-ink-500">Aucun composant disponible.</p>
        </div>
    @endif
</div>
@endsection