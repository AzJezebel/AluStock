{{-- resources/views/admin/types-composant/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Types de composant - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Types de composant</h1>
            <p class="text-xs text-admin-500 mt-0.5">{{ $types->total() }} type(s)</p>
        </div>
        <a href="{{ route('admin.types-composant.create') }}" 
           class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
            + Nouveau type
        </a>
    </div>

    {{-- Recherche --}}
    <div class="bg-white rounded border border-admin-200 p-3 mb-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="q" value="{{ request('q') }}" 
                   placeholder="Rechercher par nom ou description..." 
                   class="flex-1 min-w-[200px] px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
            <button type="submit" class="px-3 py-1.5 bg-admin-100 hover:bg-admin-200 text-admin-700 text-sm rounded transition">
                Filtrer
            </button>
            @if(request()->has('q'))
                <a href="{{ route('admin.types-composant.index') }}" class="text-xs text-admin-400 hover:text-admin-600">
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
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'direction' => $sort === 'nom' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                           class="hover:text-admin-700 inline-flex items-center gap-1">
                            Nom
                            @if($sort === 'nom') 
                                <span class="text-admin-400">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Slug</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'composants_count', 'direction' => $sort === 'composants_count' && $direction === 'asc' ? 'desc' : 'asc']) }}" 
                           class="hover:text-admin-700 inline-flex items-center gap-1">
                            Composants
                            @if($sort === 'composants_count') 
                                <span class="text-admin-400">{{ $direction === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-100">
                @forelse($types as $type)
                    <tr class="hover:bg-admin-50 transition">
                        <td class="px-4 py-2 text-sm text-admin-900 font-medium">
                            {{ $type->nom }}
                        </td>
                        <td class="px-4 py-2 text-xs text-admin-400 font-mono">
                            {{ $type->slug }}
                        </td>
                        <td class="px-4 py-2 text-sm text-admin-500 max-w-md truncate">
                            {{ $type->description ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                {{ $type->composants_count > 0 ? 'bg-amber-50 text-amber-700' : 'bg-admin-100 text-admin-500' }}">
                                {{ $type->composants_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.types-composant.edit', $type) }}" 
                               class="text-xs text-admin-500 hover:text-admin-900 mr-3">Éditer</a>
                            <form action="{{ route('admin.types-composant.destroy', $type) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Supprimer ce type ? Les composants liés perdront leur type.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-sm text-admin-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Aucun type de composant trouvé.
                            <a href="{{ route('admin.types-composant.create') }}" class="text-admin-600 hover:text-admin-800 underline ml-1">Créer le premier</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($types->hasPages())
        <div class="mt-4">{{ $types->links() }}</div>
    @endif
</div>
@endsection