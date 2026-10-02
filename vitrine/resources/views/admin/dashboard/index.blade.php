{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Dashboard - Administration')

@section('content')
@php
    $heroCount = count(\App\Support\SiteImages::get('hero'));
    $hasAbout  = \App\Support\SiteImages::first('about') !== null;
@endphp
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold text-admin-900">Dashboard</h1>
        <a href="{{ route('admin.ouvrages.create') }}"
           class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
            + Nouvel ouvrage
        </a>
    </div>

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
            {{-- Ouvrages --}}
            <a href="{{ route('admin.ouvrages.index') }}"
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-amber-50 flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['total_ouvrages'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">
                            Ouvrages
                            <span class="text-emerald-600">· {{ $stats['active_ouvrages'] ?? 0 }} actifs</span>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Médias --}}
            <a href="{{ route('admin.medias.index') }}"
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-amber-50 flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['total_medias'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Médias</div>
                    </div>
                </div>
            </a>

            {{-- Images du site (hero + à propos) --}}
            <a href="{{ route('admin.site-images.index') }}"
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-amber-50 flex items-center justify-center text-amber-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7zm0 9l5-5 4 4 3-3 6 6M15 9h.01"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-admin-900">{{ $heroCount }}</div>
                        <div class="text-xs text-admin-500">
                            Images hero
                            <span class="{{ $hasAbout ? 'text-emerald-600' : 'text-admin-400' }}">· À propos : {{ $hasAbout ? 'OK' : 'vide' }}</span>
                        </div>
                    </div>
                </div>
            </a>

            {{-- Voir le site --}}
            <a href="{{ route('vitrine.index') }}" target="_blank"
               class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 hover:shadow-sm transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded bg-admin-100 flex items-center justify-center text-admin-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-admin-900">Voir le site</div>
                        <div class="text-xs text-admin-500">Aperçu public ↗</div>
                    </div>
                </div>
            </a>
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
                        <div class="text-2xl font-bold text-admin-900">{{ $stats['total_categories'] ?? 0 }}</div>
                        <div class="text-xs text-admin-500">Catégories</div>
                    </div>
                </div>
            </a>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{-- SECTION : DERNIERS OUVRAGES AJOUTÉS --}}
    {{-- ============================================================ --}}
    <section>
        <div class="flex items-center gap-3 mb-3">
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                Derniers ouvrages ajoutés
            </h2>
            <div class="flex-1 border-t border-admin-200"></div>
            <a href="{{ route('admin.ouvrages.index') }}" class="text-xs text-admin-400 hover:text-admin-600">Voir tout</a>
        </div>

        <div class="bg-white rounded border border-admin-200">
            <div class="divide-y divide-admin-100">
                @forelse($latestOuvrages ?? [] as $ouvrage)
                    <a href="{{ route('admin.ouvrages.show', $ouvrage) }}"
                       class="flex items-center justify-between px-4 py-3 hover:bg-admin-50 transition">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="font-mono text-xs text-admin-500 bg-admin-100 px-2 py-0.5">
                                {{ $ouvrage->reference }}
                            </span>
                            <span class="text-sm text-admin-900 truncate">{{ $ouvrage->titre }}</span>
                            <span class="hidden sm:inline text-xs text-admin-400">{{ $ouvrage->categorie->nom ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $ouvrage->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-admin-100 text-admin-500' }}">
                                {{ $ouvrage->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <span class="text-xs text-admin-400">{{ $ouvrage->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-10 text-center text-sm text-admin-400">
                        Aucun ouvrage pour le moment.
                        <a href="{{ route('admin.ouvrages.create') }}" class="text-admin-600 hover:text-admin-800 underline ml-1">Créer le premier</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection