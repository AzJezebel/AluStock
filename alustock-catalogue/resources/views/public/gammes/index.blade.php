{{-- resources/views/public/gammes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gammes - AluStock catalogue aluminium industriel')

@section('page-header')
    {{-- En-tête avec statistiques inspirée de la maquette --}}
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-aluminum-900 tracking-tight">Catalogue de référence industriel</h1>
                <p class="text-aluminum-500 mt-1 text-sm">
                    Aluminium industriel, profilés et fixations — Fiches techniques EN disponibles pour chaque produit.
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-6 text-sm">
                <div class="text-center">
                    <span class="block text-2xl font-bold text-primary-600">{{ $totalReferences ?? '…' }}</span>
                    <span class="text-aluminum-400 text-xs uppercase tracking-wider">Références</span>
                </div>
                <div class="text-center">
                    <span class="block text-2xl font-bold text-primary-600">{{ $totalGammes ?? $gammes->total() }}</span>
                    <span class="text-aluminum-400 text-xs uppercase tracking-wider">Gammes</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div>
    {{-- Grille des gammes --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($gammes as $gamme)
            <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-aluminum-200 hover:border-primary-200">
                {{-- Image de couverture --}}
                <div class="relative h-48 bg-aluminum-100 overflow-hidden">
                    @if($gamme->image_cover)
                        <img src="{{ asset('storage/' . $gamme->image_cover) }}" 
                             alt="{{ $gamme->nom }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-aluminum-400 bg-gradient-to-br from-aluminum-50 to-aluminum-200">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- Badge du nombre d'ouvrages --}}
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-xs font-medium px-2.5 py-1 rounded-full shadow-sm text-aluminum-700">
                        {{ $gamme->ouvrages_count ?? 0 }} ouvrages
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-aluminum-900 mb-1">
                        <a href="{{ route('gammes.show', $gamme->slug) }}" class="hover:text-primary-600 transition">
                            {{ $gamme->nom }}
                        </a>
                    </h3>
                    
                    @if($gamme->description)
                        <p class="text-sm text-aluminum-500 line-clamp-2 mb-3">
                            {{ Str::limit($gamme->description, 100) }}
                        </p>
                    @endif

                    {{-- Métadonnées et lien --}}
                    <div class="flex items-center justify-between pt-3 border-t border-aluminum-100">
                        <span class="text-xs text-aluminum-400">
                            {{ $gamme->created_at->format('d/m/Y') }}
                        </span>
                        <a href="{{ route('gammes.show', $gamme->slug) }}" 
                           class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition">
                            Explorer
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <div class="text-aluminum-400">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-lg font-medium">Aucune gamme disponible</p>
                    <p class="text-sm mt-1">Le catalogue est en cours de construction.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(isset($gammes) && method_exists($gammes, 'links'))
        <div class="mt-8">
            {{ $gammes->links() }}
        </div>
    @endif
</div>
@endsection