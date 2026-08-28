{{-- resources/views/public/gammes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gammes - AluStock')

{{-- resources/views/public/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'AluStock — Catalogue de référence aluminium industriel')

{{-- ============================================================
     HERO
     ============================================================ --}}
@section('hero')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

        <div class="lg:col-span-2">
            <span class="text-xs font-semibold uppercase tracking-widest text-amber-500">
                Catalogue de référence industriel
            </span>
            <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-white tracking-tight">
                Aluminium industriel, profilés et fixations
            </h1>
            <p class="mt-4 text-ink-300 max-w-xl">
                Plus de 18 910 références documentées — profilés T-slot, tôles, visserie,
                connecteurs et extrusions sur mesure. Fiches techniques EN disponibles pour chaque produit.
            </p>

            <form action="{{ route('search.index') }}" method="GET" class="mt-6 flex max-w-xl">
                <input type="text"
                       name="q"
                       placeholder="Référence, alliage, dimension..."
                       class="flex-1 px-4 py-3 rounded-l-lg bg-white/5 border border-white/10 text-white placeholder-ink-400 text-sm focus:outline-none focus:ring-2 focus:ring-amber-600">
                <button type="submit" class="px-5 py-3 bg-amber-700 hover:bg-amber-800 text-white text-sm font-semibold rounded-r-lg transition">
                    Rechercher
                </button>
            </form>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white/5 border border-white/10 rounded-lg p-4 text-center">
                <span class="block text-2xl sm:text-3xl font-bold text-white">{{ $totalReferences ?? '18 910' }}</span>
                <span class="text-ink-400 text-xs uppercase tracking-wider">Références</span>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-lg p-4 text-center">
                <span class="block text-2xl sm:text-3xl font-bold text-white">{{ $gammes->count() }}</span>
                <span class="text-ink-400 text-xs uppercase tracking-wider">Gammes</span>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-lg p-4 text-center">
                <span class="block text-sm sm:text-base font-bold text-white">6063 · 6061 · 3003</span>
                <span class="text-ink-400 text-xs uppercase tracking-wider">Alliages principaux</span>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-lg p-4 text-center">
                <span class="block text-sm sm:text-base font-bold text-white">EN 755 · EN 485</span>
                <span class="text-ink-400 text-xs uppercase tracking-wider">Normes couvertes</span>
            </div>
        </div>

    </div>
</div>
@endsection

{{-- ============================================================
     CONTENU — grille des gammes
     ============================================================ --}}
@section('content')
<div>
    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-ink-900">Gammes de profilés</h1>
        <p class="text-ink-500 text-sm mt-1">Découvrez l'ensemble de nos gammes techniques.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($gammes as $gamme)
            <a href="{{ route('ouvrages.index', ['gamme' => $gamme->slug]) }}"
               class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300 flex flex-col">

                <div class="h-36 bg-ink-100 overflow-hidden">
                    @if($gamme->image_cover ?? false)
                        <img src="{{ asset('storage/' . $gamme->image_cover) }}"
                             alt="{{ $gamme->nom }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-ink-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="text-base font-semibold text-ink-900 group-hover:text-amber-700 transition">
                            {{ $gamme->nom }}
                        </h3>
                        <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800">
                            {{ number_format($gamme->ouvrages_count ?? 0, 0, ',', ' ') }} réf.
                        </span>
                    </div>

                    @if($gamme->description)
                        <p class="text-sm text-ink-500 mt-1">
                            {{ Str::limit($gamme->description, 100) }}
                        </p>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-xl border border-ink-200">
                <div class="text-ink-400">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-sm font-medium">Aucune gamme disponible</p>
                </div>
            </div>
        @endforelse
    </div>

    @if(isset($gammes) && method_exists($gammes, 'links'))
        <div class="mt-6">
            {{ $gammes->links() }}
        </div>
    @endif
</div>
@endsection