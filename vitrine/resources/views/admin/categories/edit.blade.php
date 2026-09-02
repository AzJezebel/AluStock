{{-- resources/views/admin/categories/edit.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Modifier la catégorie - AluStock Admin')
@section('page-title', 'Modifier la catégorie')
@section('page-subtitle', 'Mettre à jour la catégorie')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    
    <form action="{{ route('admin.categories.update', $categorie) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne gauche -->
            <div class="space-y-4">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $categorie->nom) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('nom')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('description', $categorie->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-4">
                <!-- Icône -->
                <div>
                    <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Icône</label>
                    <div class="flex gap-2">
                        <input type="text" id="icone" name="icone" value="{{ old('icone', $categorie->icone ?? 'fa-tag') }}"
                               placeholder="fa-tag"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <div class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg flex items-center">
                            <i class="fas {{ old('icone', $categorie->icone ?? 'fa-tag') }} text-amber-500" id="icone_preview"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Entrez une classe Font Awesome (ex: fa-tag, fa-star)</p>
                    @error('icone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordre -->
                <div>
                    <label for="ordre" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                    <input type="number" id="ordre" name="ordre" value="{{ old('ordre', $categorie->ordre ?? 0) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('ordre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $categorie->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                        <span class="text-sm text-gray-700">Actif</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="mt-8 flex items-center justify-end space-x-3 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Mettre à jour
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('icone').addEventListener('input', function() {
        document.getElementById('icone_preview').className = 'fas ' + this.value;
    });
</script>
@endpush
@endsection