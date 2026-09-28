{{-- resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Catégories - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Catégories</h1>
            <p class="text-xs text-admin-500 mt-0.5">{{ $categories->total() }} catégorie(s)</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
            + Nouvelle catégorie
        </a>
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
                    <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Icône</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Ouvrages</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-admin-100">
                @forelse($categories as $categorie)
                    <tr class="hover:bg-admin-50 transition">
                        <td class="px-4 py-2 text-sm text-admin-900 font-medium">
                            {{ $categorie->nom }}
                        </td>
                        <td class="px-4 py-2 text-xs text-admin-400 font-mono">
                            {{ $categorie->slug }}
                        </td>
                        <td class="px-4 py-2 text-sm">
                            @if($categorie->icone)
                                <span class="text-lg">{{ $categorie->icone }}</span>
                            @else
                                <span class="text-admin-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700">
                                {{ $categorie->ouvrages_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm text-admin-500 max-w-xs truncate">
                            {{ $categorie->description ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $categorie) }}" 
                               class="text-xs text-admin-500 hover:text-admin-900 mr-3">Éditer</a>
                            <form action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ? Les ouvrages liés perdront leur catégorie.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-admin-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Aucune catégorie trouvée.
                            <a href="{{ route('admin.categories.create') }}" class="text-admin-600 hover:text-admin-800 underline ml-1">Créer la première</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
        <div class="mt-4">{{ $categories->links() }}</div>
    @endif
</div>
@endsection