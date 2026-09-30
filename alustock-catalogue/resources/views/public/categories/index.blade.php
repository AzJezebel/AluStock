{{-- resources/views/public/categories/index.blade.php
     Direction "Blueprint" — page de listing pure (le hero vit sur home.blade.php). --}}
@extends('layouts.app')

@section('title', 'Catégories — AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-ink-200">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">Catégories</span>
@endsection

@section('content')
<div>
    <div class="mb-6 pb-3 border-b border-ink-800">
        <h1 class="font-display text-2xl font-bold text-fg">Toutes les catégories</h1>
        <p class="font-mono text-[11px] text-ink-500 mt-1 uppercase tracking-wider">
            Parcourir les ouvrages par catégorie
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}"
               class="card-blueprint group relative bg-ink-900 border border-ink-800 hover:border-amber-500/60 transition-colors flex flex-col">

                <span class="tick tick-tl" aria-hidden="true"></span>
                <span class="tick tick-br" aria-hidden="true"></span>

                <div class="h-36 bg-ink-50 border-b border-ink-800 overflow-hidden">
                    @if($category->image)
                        <img src="{{ asset($category->image) }}"
                             alt="{{ $category->nom }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-ink-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="text-base font-semibold text-ink-100 group-hover:text-amber-600 transition">
                            {{ $category->nom }}
                        </h3>
                        <span class="shrink-0 font-mono text-[10px] px-2 py-0.5 border border-amber-500/30 bg-amber-500/10 text-amber-400">
                            {{ number_format($category->composants_count ?? 0, 0, ',', ' ') }} réf.
                        </span>
                    </div>

                    @if($category->description)
                        <p class="text-sm text-ink-400 mt-1.5">
                            {{ Str::limit($category->description, 100) }}
                        </p>
                    @endif

                    @if(($category->subcategories ?? collect())->isNotEmpty())
                        <div class="flex flex-wrap gap-1.5 mt-4 pt-3 border-t border-ink-800">
                            @foreach($category->subcategories->take(4) as $sub)
                                <span class="font-mono px-2 py-1 bg-ink-800 text-ink-400 text-[10.5px]">
                                    {{ $sub->nom }}
                                </span>
                            @endforeach
                            @if($category->subcategories->count() > 4)
                                <span class="font-mono px-2 py-1 bg-ink-800 text-ink-500 text-[10.5px]">
                                    +{{ $category->subcategories->count() - 4 }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 bg-ink-900 border border-ink-800">
                <div class="text-ink-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-sm font-medium">Aucune catégorie disponible</p>
                </div>
            </div>
        @endforelse
    </div>

    @if(isset($categories) && method_exists($categories, 'links'))
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection