{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard - Administration')

@section('content')
<div>
    <h1 class="text-lg font-semibold text-admin-900 mb-6">Dashboard</h1>

    {{-- ============================================================ --}}
    {{-- SECTION : CONTENU PRINCIPAL --}}
    {{-- ============================================================ --}}
    <section class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                Contenu principal
            </h2>
            <div class="flex-1 border-t border-admin-200"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('admin.ouvrages.index') }}" 
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-amber-50 flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['ouvrages'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Ouvrages</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.composants.index') }}" 
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-amber-50 flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['composants'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Composants</div>
                    </div>
                </div>
            </a>

            <a href="#"
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-amber-50 flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['medias'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Médias</div>
                    </div>
                </div>
            </a>

            
            <div class="bg-admin-50 rounded border border-dashed border-admin-200 p-4 flex items-center justify-center text-admin-300 text-xs">
                Futur : Documents
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION : CLASSIFICATION --}}
    {{-- ============================================================ --}}
    <section class="mb-8">
        <div class="flex items-center gap-3 mb-3">
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                Classification
            </h2>
            <div class="flex-1 border-t border-admin-200"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            <a href="{{ route('admin.categories.index') }}" 
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-blue-50 flex items-center justify-center text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['categories'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Catégories</div>
                    </div>
                </div>
            </a>

            
            <div class="bg-admin-50 rounded border border-dashed border-admin-200 p-4 flex items-center justify-center text-admin-300 text-xs">
                Futur : Gammes
            </div>

            <a href="{{ route('admin.types-composant.index') }}" 
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-blue-50 flex items-center justify-center text-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['types_composant'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Types de composant</div>
                    </div>
                </div>
            </a>

            {{-- Placeholder pour futurs ajouts (Finitions, etc.) --}}
            <div class="bg-admin-50 rounded border border-dashed border-admin-200 p-4 flex items-center justify-center text-admin-300 text-xs">
                Futur : Finitions
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION : DERNIERS OUVRAGES MODIFIÉS --}}
    {{-- ============================================================ --}}
    @if(isset($derniersOuvrages) && $derniersOuvrages->count())
        <section>
            <div class="flex items-center gap-3 mb-3">
                <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                    Derniers ouvrages modifiés
                </h2>
                <div class="flex-1 border-t border-admin-200"></div>
            </div>

            <div class="bg-white rounded border border-admin-200">
                <div class="divide-y divide-admin-100">
                    @foreach($derniersOuvrages as $ouvrage)
                        <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" 
                           class="flex items-center justify-between px-4 py-3 hover:bg-admin-50 transition">
                            <div class="flex items-center gap-3">
                                <span class="font-mono text-xs text-admin-500 bg-admin-100 px-2 py-0.5">
                                    {{ $ouvrage->reference }}
                                </span>
                                <span class="text-sm text-admin-900">{{ $ouvrage->nom }}</span>
                            </div>
                            <span class="text-xs text-admin-400">
                                {{ $ouvrage->updated_at->diffForHumans() }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection