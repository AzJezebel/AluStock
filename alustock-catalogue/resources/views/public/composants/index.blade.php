{{-- resources/views/public/composants/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Composants - AluStock')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-5 pb-3 border-b border-ink-200">
        <h1 class="text-2xl font-bold text-ink-900">Tous les composants</h1>
        <p class="text-ink-500 text-sm mt-1">
            {{ $composants->total() }} référence(s) documentée(s)
        </p>
    </div>

    @if($composants->count())
        {{-- Grille --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
            @foreach($composants as $composant)
                @php
                    $media = $composant->medias->firstWhere('est_principal', true) 
                          ?? $composant->medias->sortBy('pivot.ordre')->first();
                @endphp
                
                <a href="{{ route('composants.show', $composant->slug) }}"
                   class="group bg-white border border-ink-200 hover:border-amber-500 hover:shadow-md transition-all flex flex-col">

                    {{-- Thumbnail --}}
                    <div class="aspect-square bg-ink-50 border-b border-ink-100 overflow-hidden flex items-center justify-center">
                        @if($media)
                            <img src="{{ asset('storage/' . $media->chemin_fichier) }}" 
                                 alt="{{ $media->titre ?? $composant->designation }}"
                                 class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-300">
                        @else
                            <svg class="w-10 h-10 text-ink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Contenu --}}
                    <div class="p-3 flex flex-col flex-1">

                        {{-- Référence + type --}}
                        <div class="flex items-start justify-between gap-2 mb-2 min-w-0">
                            {{-- Référence : peut être tronquée si trop longue --}}
                            <span class="font-mono text-[10px] font-bold text-ink-600 bg-ink-100 px-1.5 py-0.5 tracking-wider truncate max-w-[60%]">
                                {{ $composant->reference }}
                            </span>
                        
                            {{-- Catégorie : largeur limitée + tronquée + alignée à droite --}}
                            @if($composant->typeComposant)
                                <span class="text-[10px] text-ink-400 uppercase tracking-wider font-semibold truncate max-w-[40%] text-right shrink-0">
                                    {{ $composant->typeComposant->nom }}
                                </span>
                            @endif
                        </div>

                        {{-- Désignation --}}
                        <h3 class="text-sm font-semibold text-ink-900 leading-snug line-clamp-2 group-hover:text-amber-700 transition-colors">
                            {{ $composant->designation }}
                        </h3>

                        {{-- Matière --}}
                        @if($composant->matiere)
                            <p class="text-xs text-ink-500 mt-1.5 truncate">
                                {{ $composant->matiere }}
                            </p>
                        @endif

                        {{-- Footer --}}
                        <div class="mt-auto pt-2 border-t border-ink-100 flex items-center justify-between">
                            <span class="text-[10px] text-ink-500 font-medium truncate">
                                {{ $composant->gamme?->nom ?? 'Sans gamme' }}
                            </span>
                            <span class="text-[10px] text-amber-700 font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap ml-1">
                                Voir →
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-5">{{ $composants->appends(request()->query())->links() }}</div>
    @else
        <div class="text-center py-12 bg-white border border-ink-200">
            <p class="text-ink-500 text-sm">Aucun composant disponible.</p>
        </div>
    @endif
</div>
@endsection