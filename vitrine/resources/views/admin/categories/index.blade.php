{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layouts.admin')

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

    {{-- Recherche --}}
    <div class="bg-white rounded border border-admin-200 p-3 mb-4">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher une catégorie..."
                   class="flex-1 min-w-[200px] px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
            <button type="submit" class="px-3 py-1.5 bg-admin-100 hover:bg-admin-200 text-admin-700 text-sm rounded transition">
                Filtrer
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.categories.index') }}" class="text-xs text-admin-400 hover:text-admin-600">Réinitialiser</a>
            @endif
        </form>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded border border-admin-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-admin-200">
                <thead class="bg-admin-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Nom</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Icône</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Ordre</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-100">
                    @forelse($categories as $categorie)
                        <tr class="hover:bg-admin-50 transition">
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.categories.edit', $categorie) }}"
                                   class="text-sm font-medium text-admin-900 hover:text-amber-700 transition">{{ $categorie->nom }}</a>
                                @if($categorie->description)
                                    <p class="text-xs text-admin-400 truncate max-w-xs">{{ Str::limit($categorie->description, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-admin-500 font-mono whitespace-nowrap">{{ $categorie->slug }}</td>
                            <td class="px-4 py-2 text-xs text-admin-500 font-mono whitespace-nowrap">
                                {{ $categorie->icone ?: '—' }}
                            </td>
                            <td class="px-4 py-2 text-center text-sm text-admin-500">{{ $categorie->ordre ?? 0 }}</td>
                            <td class="px-4 py-2 text-center">
                                <button type="button"
                                        data-toggle-url="{{ route('admin.categories.toggle-status', $categorie) }}"
                                        title="Cliquer pour {{ $categorie->is_active ? 'désactiver' : 'activer' }}"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium transition {{ $categorie->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-admin-100 text-admin-500 hover:bg-admin-200' }}">
                                    {{ $categorie->is_active ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>
                            <td class="px-4 py-2 text-right whitespace-nowrap">
                                <a href="{{ route('admin.categories.edit', $categorie) }}"
                                   class="text-xs text-admin-500 hover:text-admin-900 mr-3">Éditer</a>
                                <form action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Supprimer cette catégorie ?')">
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
    </div>

    @if($categories->hasPages())
        <div class="mt-4">{{ $categories->withQueryString()->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    document.querySelectorAll('[data-toggle-url]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            btn.disabled = true;
            fetch(btn.dataset.toggleUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            })
                .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
                .then(function (data) { if (data.success) location.reload(); else btn.disabled = false; })
                .catch(function () { btn.disabled = false; alert('Le changement a échoué, réessayez.'); });
        });
    });
});
</script>
@endpush