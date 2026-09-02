{{-- resources/views/admin/gammes/create.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Ajouter une gamme - AluStock Admin')
@section('page-title', 'Ajouter une gamme')
@section('page-subtitle', 'Créer une nouvelle gamme pour vos ouvrages')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.gammes.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne gauche -->
            <div class="space-y-4">
                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('nom')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('description') }}</textarea>
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
                        <input type="text" id="icone" name="icone" value="{{ old('icone', 'fa-cube') }}"
                               placeholder="fa-cube"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <div class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg flex items-center">
                            <i class="fas {{ old('icone', 'fa-cube') }} text-amber-500" id="icone_preview"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Entrez une classe Font Awesome (ex: fa-cube, fa-cubes)</p>
                    @error('icone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Couleur -->
                <div>
                    <label for="couleur" class="block text-sm font-medium text-gray-700 mb-1">Couleur</label>
                    <div class="flex gap-2">
                        <input type="color" id="couleur" name="couleur" value="{{ old('couleur', '#F59E0B') }}"
                               class="w-12 h-12 p-1 border border-gray-300 rounded-lg cursor-pointer">
                        <input type="text" name="couleur_text" value="{{ old('couleur', '#F59E0B') }}"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                               id="couleur_text">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Sélectionnez une couleur pour identifier cette gamme</p>
                    @error('couleur')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ordre -->
                <div>
                    <label for="ordre" class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                    <input type="number" id="ordre" name="ordre" value="{{ old('ordre', 0) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('ordre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                        <span class="text-sm text-gray-700">Actif</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="mt-8 flex items-center justify-end space-x-3 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.gammes.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Créer la gamme
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Prévisualisation de l'icône
    document.getElementById('icone').addEventListener('input', function() {
        document.getElementById('icone_preview').className = 'fas ' + this.value;
    });

    // Synchronisation des couleurs
    const colorPicker = document.getElementById('couleur');
    const colorText = document.getElementById('couleur_text');
    
    colorPicker.addEventListener('input', function() {
        colorText.value = this.value;
    });
    
    colorText.addEventListener('input', function() {
        colorPicker.value = this.value;
    });
</script>
@endpush
@endsection