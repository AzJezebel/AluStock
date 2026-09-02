{{-- resources/views/admin/gammes/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Gestion des gammes - AluStock Admin')
@section('page-title', 'Gestion des gammes')
@section('page-subtitle', 'Organisez vos ouvrages par gammes')

@section('content')
<div class="space-y-6">
    <!-- Barre d'outils -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Recherche -->
                <form action="{{ route('admin.gammes.index') }}" method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher une gamme..."
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent w-64">
                    <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            
            <a href="{{ route('admin.gammes.create') }}" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Ajouter une gamme
            </a>
        </div>
    </div>

    <!-- Tableau des gammes -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase w-16">#</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Nom</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Icône</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Couleur</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Statut</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Ordre</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($gammes as $gamme)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-sm text-gray-500">{{ $gamme->id }}</td>
                        <td class="py-3 px-4">
                            <span class="text-gray-800 font-medium">{{ $gamme->nom }}</span>
                            @if($gamme->description)
                                <p class="text-xs text-gray-400">{{ Str::limit($gamme->description, 50) }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-500">{{ $gamme->slug }}</td>
                        <td class="py-3 px-4 text-sm text-gray-500">
                            @if($gamme->icone)
                                <i class="fas {{ $gamme->icone }} text-xl text-amber-500"></i>
                            @else
                                <i class="fas fa-cube text-gray-300"></i>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full border border-gray-200" 
                                      style="background-color: {{ $gamme->couleur ?? '#6B7280' }}"></span>
                                <span class="text-sm text-gray-500">{{ $gamme->couleur ?? 'Non définie' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <button onclick="toggleStatus({{ $gamme->id }})" 
                                    class="px-3 py-1 text-xs rounded-full transition-colors {{ $gamme->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $gamme->is_active ? 'Actif' : 'Inactif' }}
                            </button>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-500">{{ $gamme->ordre }}</td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.gammes.edit', $gamme) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition-colors" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.gammes.destroy', $gamme) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
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
                        <td colspan="8" class="py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl block mb-3"></i>
                            <p>Aucune gamme trouvée</p>
                            <a href="{{ route('admin.gammes.create') }}" class="mt-2 inline-block text-amber-500 hover:text-amber-600">
                                Créer votre première gamme
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $gammes->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleStatus(id) {
        fetch(`/admin/gammes/${id}/toggle-status`, {
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
        return confirm('Êtes-vous sûr de vouloir supprimer cette gamme ?');
    }
</script>
@endpush
@endsection