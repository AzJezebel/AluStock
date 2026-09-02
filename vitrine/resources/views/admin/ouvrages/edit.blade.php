{{-- resources/views/admin/ouvrages/edit.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Modifier l\'ouvrage - AluStock Admin')
@section('page-title', 'Modifier l\'ouvrage')
@section('page-subtitle', 'Mettre à jour la réalisation')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.ouvrages.update', $ouvrage) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Colonne gauche -->
            <div class="space-y-4">
                <!-- Titre -->
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
                    <input type="text" id="titre" name="titre" value="{{ old('titre', $ouvrage->titre) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('titre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Référence -->
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Référence *</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference', $ouvrage->reference) }}" required
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
                            <option value="{{ $categorie->id }}" {{ old('categorie_id', $ouvrage->categorie_id) == $categorie->id ? 'selected' : '' }}>
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
                            <option value="{{ $gamme->id }}" {{ old('gamme_id', $ouvrage->gamme_id) == $gamme->id ? 'selected' : '' }}>
                                {{ $gamme->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date de réalisation -->
                <div>
                    <label for="date_realisation" class="block text-sm font-medium text-gray-700 mb-1">Date de réalisation</label>
                    <input type="date" id="date_realisation" name="date_realisation" value="{{ old('date_realisation', $ouvrage->date_realisation?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="space-y-4">
                <!-- Client -->
                <div>
                    <label for="client" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <input type="text" id="client" name="client" value="{{ old('client', $ouvrage->client) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Localisation -->
                <div>
                    <label for="localisation" class="block text-sm font-medium text-gray-700 mb-1">Localisation</label>
                    <input type="text" id="localisation" name="localisation" value="{{ old('localisation', $ouvrage->localisation) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('description', $ouvrage->description) }}</textarea>
                </div>

                <!-- Spécifications -->
                <div>
                    <label for="specifications" class="block text-sm font-medium text-gray-700 mb-1">Spécifications</label>
                    <textarea id="specifications" name="specifications" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">{{ old('specifications', $ouvrage->specifications) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Image principale -->
        <div class="mt-6">
            @if($ouvrage->mainImage)
                <div class="mb-2">
                    <p class="text-sm text-gray-600">Image actuelle :</p>
                    <img src="{{ asset('storage/' . $ouvrage->mainImage->file_path) }}" alt="Image actuelle" class="h-32 w-auto object-cover rounded-lg">
                </div>
            @endif
            <label for="main_image" class="block text-sm font-medium text-gray-700 mb-1">Changer l'image principale</label>
            <input type="file" id="main_image" name="main_image" accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Max: 2MB</p>
        </div>

        <!-- Galerie -->
        <div class="mt-6">
            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-1">Ajouter à la galerie</label>
            <input type="file" id="gallery_images" name="gallery_images[]" multiple accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            <p class="text-xs text-gray-500 mt-1">Ajouter des images supplémentaires (JPG, PNG, GIF). Max: 2MB par image</p>
            
            @if($ouvrage->gallery->count() > 0)
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-2">Images existantes :</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ouvrage->gallery as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="Galerie" class="h-20 w-20 object-cover rounded-lg">
                                <form action="{{ route('admin.ouvrages.delete-image', [$ouvrage, $image]) }}" method="POST" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-5 h-5 bg-red-500 text-white rounded-full text-xs hover:bg-red-600" onclick="return confirm('Supprimer cette image ?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Options -->
        <div class="mt-6 flex flex-wrap gap-4">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ouvrage->is_active) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                <span class="text-sm text-gray-700">Actif</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $ouvrage->is_featured) ? 'checked' : '' }}
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
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection