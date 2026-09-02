{{-- resources/views/admin/ouvrages/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Gestion des ouvrages - AluStock Admin')
@section('page-title', 'Gestion des ouvrages')
@section('page-subtitle', 'Gérez vos réalisations et ouvrages')

@section('content')
<div class="space-y-6">
    <!-- Barre d'outils -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Recherche -->
                <form action="{{ route('admin.ouvrages.index') }}" method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher un ouvrage..."
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent w-64">
                    <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                
                <!-- Filtres -->
                <select name="categorie" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>
                
                <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            
            <a href="{{ route('admin.ouvrages.create') }}" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un ouvrage
            </a>
        </div>
    </div>

    <!-- Tableau des ouvrages -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Ouvrage</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Référence</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Gamme</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Statut</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Vedette</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ouvrages as $ouvrage)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                                    @if($ouvrage->mainImage)
                                        <img src="{{ asset('storage/' . $ouvrage->mainImage->file_path) }}" alt="{{ $ouvrage->titre }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-image text-gray-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('admin.ouvrages.show', $ouvrage) }}" class="text-gray-800 hover:text-amber-600 font-medium">
                                        {{ $ouvrage->titre }}
                                    </a>
                                    <p class="text-xs text-gray-400">{{ $ouvrage->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $ouvrage->reference }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $ouvrage->categorie->nom ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $ouvrage->gamme->nom ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <button onclick="toggleStatus({{ $ouvrage->id }})" class="px-3 py-1 text-xs rounded-full transition-colors {{ $ouvrage->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $ouvrage->is_active ? 'Actif' : 'Inactif' }}
                            </button>
                        </td>
                        <td class="py-3 px-4">
                            <button onclick="toggleFeatured({{ $ouvrage->id }})" class="text-sm {{ $ouvrage->is_featured ? 'text-amber-500' : 'text-gray-400' }}">
                                <i class="fas {{ $ouvrage->is_featured ? 'fa-star' : 'fa-star-o' }}"></i>
                            </button>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.ouvrages.show', $ouvrage) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.ouvrages.destroy', $ouvrage) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl block mb-3"></i>
                            <p>Aucun ouvrage trouvé</p>
                            <a href="{{ route('admin.ouvrages.create') }}" class="mt-2 inline-block text-amber-500 hover:text-amber-600">
                                Créer votre premier ouvrage
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $ouvrages->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleStatus(id) {
        fetch(`/admin/ouvrages/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function toggleFeatured(id) {
        fetch(`/admin/ouvrages/${id}/toggle-featured`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function confirmDelete() {
        return confirm('Êtes-vous sûr de vouloir supprimer cet ouvrage ?');
    }
</script>
@endpush
@endsection