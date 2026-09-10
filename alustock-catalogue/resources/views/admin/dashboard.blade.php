@extends('layouts.admin')

@section('title', 'Dashboard - Administration')

@section('content')
<div>
    <h1 class="text-lg font-semibold text-admin-900 mb-4">Dashboard</h1>

    {{-- Compteurs --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <a href="{{ route('admin.ouvrages.index') }}" 
           class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 transition">
            <div class="text-2xl font-bold text-admin-900">{{ $stats['ouvrages'] }}</div>
            <div class="text-xs text-admin-500 mt-1">Ouvrages</div>
        </a>
        <a href="{{ route('admin.composants.index') }}" 
           class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 transition">
            <div class="text-2xl font-bold text-admin-900">{{ $stats['composants'] }}</div>
            <div class="text-xs text-admin-500 mt-1">Composants</div>
        </a>
        <a href="{{ route('admin.gammes.index') }}" 
           class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 transition">
            <div class="text-2xl font-bold text-admin-900">{{ $stats['gammes'] }}</div>
            <div class="text-xs text-admin-500 mt-1">Gammes</div>
        </a>
        <a href="{{ route('admin.categories.index') }}" 
           class="bg-white rounded border border-admin-200 p-4 hover:border-admin-400 transition">
            <div class="text-2xl font-bold text-admin-900">{{ $stats['categories'] }}</div>
            <div class="text-xs text-admin-500 mt-1">Catégories</div>
        </a>
    </div>

    {{-- Derniers ouvrages modifiés --}}
    <div class="bg-white rounded border border-admin-200">
        <div class="px-4 py-3 border-b border-admin-200">
            <h2 class="text-sm font-semibold text-admin-700">Derniers ouvrages modifiés</h2>
        </div>
        <div class="divide-y divide-admin-100">
            @forelse($derniersOuvrages as $ouvrage)
                <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" 
                   class="flex items-center justify-between px-4 py-3 hover:bg-admin-50 transition">
                    <div>
                        <div class="text-sm font-medium text-admin-900">{{ $ouvrage->nom }}</div>
                        <div class="text-xs text-admin-400 font-mono">{{ $ouvrage->reference }}</div>
                    </div>
                    <div class="text-xs text-admin-400">
                        {{ $ouvrage->updated_at->diffForHumans() }}
                    </div>
                </a>
            @empty
                <div class="px-4 py-6 text-center text-sm text-admin-400">
                    Aucun ouvrage.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection