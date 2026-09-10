{{-- resources/views/admin/ouvrages/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Ouvrages - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Ouvrages</h1>
            <p class="text-xs text-admin-500 mt-0.5">{{ $ouvrages->total() }} ouvrage(s)</p>
        </div>
        <a href="{{ route('admin.ouvrages.create') }}" 
           class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
            + Nouvel ouvrage
        </a>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded border border-admin-200 p-3 mb-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="q" value="{{ request('q') }}" 
                   placeholder="Rechercher par nom ou référence..." 
                   class="flex-1 min-w-[200px] px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
            
            <select name="gamme" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Toutes les gammes</option>
                @foreach($gammes as $gamme)
                    <option value="{{ $gamme->id }}" {{ request('gamme') == $gamme->id ? 'selected' : '' }}>
                        {{ $gamme->nom }}
                    </option>
                @endforeach
            </select>

            <select name="categorie" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nom }}
                    </option>
                @endforeach
            </select>

            <select name="statut" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Tous les statuts</option>
                <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actifs</option>
                <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactifs</option>
            </select>

            <button type="submit" class="px-3 py-1.5 bg-admin-100 hover:bg-admin-200 text-admin-700 text-sm rounded transition">
                Filtrer
            </button>
            
            @if(request()->hasAny(['q', 'gamme', 'categorie', 'statut']))
                <a href="{{ route('admin.ouvrages.index') }}" class="text-xs text-admin-400 hover:text-admin-600">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded border border-admin-200 overflow-hidden">
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
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'direction' => $sort === 'nom' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                           class="hover:text-admin-700 inline-flex items-center gap-1">
                            Nom
                            @if($sort === 'nom') 
                                <span class="text-admin-400">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Gamme</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Composition</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Statut</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-100">
                @forelse($ouvrages as $ouvrage)
                    <tr class="hover:bg-admin-50 transition">
                        <td class="px-4 py-2 text-sm text-admin-600 font-mono whitespace-nowrap">
                            {{ $ouvrage->reference }}
                        </td>
                        <td class="px-4 py-2 text-sm text-admin-900 font-medium">
                            {{ $ouvrage->nom }}
                        </td>
                        <td class="px-4 py-2 text-sm text-admin-500">
                            @if($ouvrage->gamme)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-admin-100 text-admin-600">
                                    {{ $ouvrage->gamme->nom }}
                                </span>
                            @else
                                <span class="text-admin-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-admin-500">
                            @if($ouvrage->categorie)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-admin-100 text-admin-600">
                                    {{ $ouvrage->categorie->nom }}
                                </span>
                            @else
                                <span class="text-admin-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center text-sm text-admin-500">
                            {{ $ouvrage->composants_count ?? 0 }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if($ouvrage->est_actif)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">Actif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-admin-100 text-admin-500">Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" 
                               class="text-xs text-admin-500 hover:text-admin-900 mr-3">Éditer</a>
                            <form action="{{ route('admin.ouvrages.destroy', $ouvrage) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Supprimer cet ouvrage ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-admin-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Aucun ouvrage trouvé.
                            <a href="{{ route('admin.ouvrages.create') }}" class="text-admin-600 hover:text-admin-800 underline ml-1">Créer le premier</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($ouvrages->hasPages())
        <div class="mt-4">
            {{ $ouvrages->links() }}
        </div>
    @endif
</div>
@endsection