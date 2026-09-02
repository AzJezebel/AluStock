{{-- resources/views/admin/ouvrages/create.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Ajouter un ouvrage - AluStock Admin')
@section('page-title', 'Ajouter un ouvrage')
@section('page-subtitle', 'Créer une nouvelle réalisation')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.ouvrages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne gauche -->
            <div class="space-y-4">
                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('titre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Référence -->
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Référence *</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('reference')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie -->
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select id="categorie_id" name="categorie_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ old('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gamme -->
                <div>
                    <label for="gamme_id" class="block text-sm font-medium text-gray-700 mb-1">Gamme</label>
                    <select id="gamme_id" name="gamme_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                        <option value="">Sélectionner une gamme</option>
                        @foreach($gammes as $gamme)
                            <option value="{{ $gamme->id }}" {{ old('gamme_id') == $gamme->id ? 'selected' : '' }}>
                                {{ $gamme->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date de réalisation -->
                <div>
                    <label for="date_realisation" class="block text-sm font-medium text-gray-700 mb-1">Date de réalisation</label>
                    <input type="date" id="date_realisation" name="date_realisation" value="{{ old('date_realisation') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-4">
                <!-- Client -->
                <div>
                    <label for="client" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <input type="text" id="client" name="client" value="{{ old('client') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Localisation -->
                <div>
                    <label for="localisation" class="block text-sm font-medium text-gray-700 mb-1">Localisation</label>
                    <input type="text" id="localisation" name="localisation" value="{{ old('localisation') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('description') }}</textarea>
                </div>

                <!-- Spécifications -->
                <div>
                    <label for="specifications" class="block text-sm font-medium text-gray-700 mb-1">Spécifications</label>
                    <textarea id="specifications" name="specifications" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('specifications') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Image principale -->
        <div class="mt-6">
            <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Image principale</label>
            <input type="file" id="main_image" name="main_image" accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Max: 2MB</p>
            @error('main_image')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Galerie -->
        <div class="mt-6">
            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-1">Galerie d'images</label>
            <input type="file" id="gallery_images" name="gallery_images[]" multiple accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            <p class="text-xs text-gray-500 mt-1">Sélectionnez plusieurs images (JPG, PNG, GIF). Max: 2MB par image</p>
            @error('gallery_images.*')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Options -->
        <div class="mt-6 flex flex-wrap gap-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : 'checked' }}
                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                <span class="text-sm text-gray-700">Actif</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                <span class="text-sm text-gray-700">Mettre en vedette</span>
            </label>
        </div>

        <!-- Boutons -->
        <div class="mt-8 flex items-center justify-end space-x-3 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.ouvrages.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Créer l'ouvrage
            </button>
        </div>
    </form>
</div>
@endsection