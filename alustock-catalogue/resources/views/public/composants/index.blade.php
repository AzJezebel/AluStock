{{-- resources/views/public/composants/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Composants - AluStock')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-4">
        <h1 class="text-xl font-bold text-slate-900">Tous les composants</h1>
        <p class="text-slate-500 text-sm mt-0.5">
            {{ $composants->total() }} référence(s) documentée(s)
        </p>
    </div>

    @if($composants->count())
        {{-- Grille compacte 5 colonnes --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-px bg-slate-200 border border-slate-200">
            @foreach($composants as $composant)
                <a href="{{ route('composants.show', $composant->slug) }}"
                   class="group bg-white hover:bg-slate-50 transition-colors p-2.5 flex flex-col">

                    {{-- Référence (badge compact) --}}
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="font-mono text-[10px] font-semibold text-slate-500 bg-slate-100 px-1.5 py-0.5 tracking-wider">
                            {{ $composant->reference }}
                        </span>
                        @if($composant->typeComposant)
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">
                                {{ $composant->typeComposant->nom }}
                            </span>
                        @endif
                    </div>

                    {{-- Désignation --}}
                    <h3 class="text-xs font-semibold text-slate-900 leading-tight line-clamp-2 group-hover:text-blue-700 transition-colors min-h-[2rem]">
                        {{ $composant->designation }}
                    </h3>

                    {{-- Footer --}}
                    <div class="mt-2 pt-1.5 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] text-slate-400">
                            {{ $composant->gamme?->nom ?? '—' }}
                        </span>
                        <span class="text-[10px] text-blue-700 opacity-0 group-hover:opacity-100 transition-opacity font-medium">
                            →
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-4">{{ $composants->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-white border border-slate-200">
            <p class="text-slate-500 text-sm">Aucun composant disponible.</p>
        </div>
    @endif
</div>
@endsection