{{-- resources/views/admin/composants/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Composants - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Composants</h1>
            <p class="text-xs text-admin-500 mt-0.5">{{ $composants->total() }} composant(s)</p>
        </div>
        <a href="{{ route('admin.composants.create') }}" 
           class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
            + Nouveau composant
        </a>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded border border-admin-200 p-3 mb-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="q" value="{{ request('q') }}" 
                   placeholder="Rechercher par référence ou désignation..." 
                   class="flex-1 min-w-[200px] px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
            
            <select name="type" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Tous les types</option>
                @foreach($typesComposant as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                        {{ $type->nom }}
                    </option>
                @endforeach
            </select>

            <select name="statut" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Tous les statuts</option>
                <option value="disponible" {{ request('statut') === 'disponible' ? 'selected' : '' }}>Disponibles</option>
                <option value="indisponible" {{ request('statut') === 'indisponible' ? 'selected' : '' }}>Indisponibles</option>
            </select>

            <button type="submit" class="px-3 py-1.5 bg-admin-100 hover:bg-admin-200 text-admin-700 text-sm rounded transition">
                Filtrer
            </button>
            
            @if(request()->hasAny(['q', 'type', 'statut']))
                <a href="{{ route('admin.composants.index') }}" class="text-xs text-admin-400 hover:text-admin-600">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded border border-admin-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-admin-200">
                <thead class="bg-admin-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'reference', 'direction' => $sort === 'reference' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                               class="hover:text-admin-700 inline-flex items-center gap-1">
                                Référence
                                @if($sort === 'reference') 
                                    <span class="text-admin-400">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'designation', 'direction' => $sort === 'designation' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                               class="hover:text-admin-700 inline-flex items-center gap-1">
                                Désignation
                                @if($sort === 'designation') 
                                    <span class="text-admin-400">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Gamme</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">KG/M</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">WT/FT</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-100">
                    @forelse($composants as $composant)
                        <tr class="hover:bg-admin-50 transition">
                            <td class="px-4 py-2 text-sm text-admin-600 font-mono whitespace-nowrap">
                                {{ $composant->reference }}
                            </td>
                            <td class="px-4 py-2 text-sm text-admin-900 font-medium">
                                {{ $composant->designation }}
                            </td>
                            <td class="px-4 py-2 text-sm text-admin-500">
                                @if($composant->typeComposant)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-admin-100 text-admin-600">
                                        {{ $composant->typeComposant->nom }}
                                    </span>
                                @else
                                    <span class="text-admin-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-admin-500">
                                {{ $composant->gamme?->nom ?? '—' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-admin-600 text-right font-mono">
                                {{ $composant->poids_lineaire_kg_m ?? '—' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-admin-600 text-right font-mono">
                                {{ $composant->poids_lineaire_lbs_ft ?? '—' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($composant->est_disponible)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Dispo</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-admin-100 text-admin-500">Indispo</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">
                                <a href="{{ route('admin.composants.edit', $composant) }}" 
                                   class="text-xs text-admin-500 hover:text-admin-900 mr-3">Éditer</a>
                                <form action="{{ route('admin.composants.destroy', $composant) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Supprimer ce composant ? Cette action est irréversible.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-sm text-admin-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Aucun composant trouvé.
                                <a href="{{ route('admin.composants.create') }}" class="text-admin-600 hover:text-admin-800 underline ml-1">Créer le premier</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($composants->hasPages())
        <div class="mt-4">
            {{ $composants->links() }}
        </div>
    @endif
</div>
@endsection