{{-- resources/views/admin/ouvrages/index.blade.php --}}
@extends('admin.layouts.admin')

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
        <form method="GET" action="{{ route('admin.ouvrages.index') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Rechercher par titre ou référence..."
                   class="flex-1 min-w-[200px] px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">

            <select name="categorie" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
            </select>

            <select name="featured" class="px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                <option value="">Vedette : tous</option>
                <option value="true" {{ request('featured') === 'true' ? 'selected' : '' }}>En vedette</option>
                <option value="false" {{ request('featured') === 'false' ? 'selected' : '' }}>Pas en vedette</option>
            </select>

            <button type="submit" class="px-3 py-1.5 bg-admin-100 hover:bg-admin-200 text-admin-700 text-sm rounded transition">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'categorie', 'status', 'featured']))
                <a href="{{ route('admin.ouvrages.index') }}" class="text-xs text-admin-400 hover:text-admin-600">
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
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Ouvrage</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Référence</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-admin-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Statut</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-admin-500 uppercase tracking-wider">Vedette</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-admin-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-admin-100">
                    @forelse($ouvrages as $ouvrage)
                        <tr class="hover:bg-admin-50 transition">
                            {{-- Ouvrage : miniature + titre --}}
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 shrink-0 rounded bg-admin-100 border border-admin-200 overflow-hidden flex items-center justify-center text-admin-300">
                                        @if($ouvrage->mainImage)
                                            <img src="{{ asset('storage/' . $ouvrage->mainImage->file_path) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.ouvrages.show', $ouvrage) }}"
                                           class="text-sm font-medium text-admin-900 hover:text-amber-700 transition block truncate max-w-xs">
                                            {{ $ouvrage->titre }}
                                        </a>
                                        <p class="text-xs text-admin-400">{{ $ouvrage->created_at->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-2 text-sm text-admin-600 font-mono whitespace-nowrap">
                                {{ $ouvrage->reference }}
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

                            {{-- Statut (clic = bascule) --}}
                            <td class="px-4 py-2 text-center">
                                <button type="button"
                                        data-toggle-url="{{ route('admin.ouvrages.toggle-status', $ouvrage) }}"
                                        title="Cliquer pour {{ $ouvrage->is_active ? 'désactiver' : 'activer' }}"
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium transition {{ $ouvrage->is_active ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-admin-100 text-admin-500 hover:bg-admin-200' }}">
                                    {{ $ouvrage->is_active ? 'Actif' : 'Inactif' }}
                                </button>
                            </td>

                            {{-- Vedette (clic = bascule) --}}
                            <td class="px-4 py-2 text-center">
                                <button type="button"
                                        data-toggle-url="{{ route('admin.ouvrages.toggle-featured', $ouvrage) }}"
                                        title="{{ $ouvrage->is_featured ? 'Retirer des vedettes' : 'Mettre en vedette' }}"
                                        class="p-1 rounded transition {{ $ouvrage->is_featured ? 'text-amber-500 hover:bg-amber-50' : 'text-admin-300 hover:text-amber-500 hover:bg-admin-100' }}">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linejoin="round"
                                         fill="{{ $ouvrage->is_featured ? 'currentColor' : 'none' }}">
                                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.098 9.1c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            </td>

                            <td class="px-4 py-2 text-right whitespace-nowrap">
                                <a href="{{ route('admin.ouvrages.show', $ouvrage) }}"
                                   class="text-xs text-admin-500 hover:text-admin-900 mr-3">Voir</a>
                                <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}"
                                   class="text-xs text-admin-500 hover:text-admin-900 mr-3">Éditer</a>
                                <form action="{{ route('admin.ouvrages.destroy', $ouvrage) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Supprimer cet ouvrage et ses images ? Cette action est irréversible.')">
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
    </div>

    {{-- Pagination --}}
    @if($ouvrages->hasPages())
        <div class="mt-4">
            {{ $ouvrages->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // Statut et vedette : un clic bascule la valeur puis recharge la liste
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