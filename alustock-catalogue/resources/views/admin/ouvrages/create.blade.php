{{-- resources/views/admin/ouvrages/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nouvel ouvrage - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Nouvel ouvrage</h1>
            <p class="text-xs text-admin-500 mt-0.5">Créer un nouvel ouvrage dans le catalogue</p>
        </div>
        <a href="{{ route('admin.ouvrages.index') }}" 
           class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
    </div>

    {{-- ============================================================ --}}
    {{-- FORMULAIRE PRINCIPAL --}}
    {{-- ============================================================ --}}
    <form action="{{ route('admin.ouvrages.store') }}" method="POST" 
          enctype="multipart/form-data"
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-4xl">
        @csrf

        {{-- Inputs cachés pour la soumission --}}
        <input type="hidden" name="temp_composition" id="temp-composition-input" value="[]">
        <input type="hidden" name="temp_caracteristiques" id="temp-caracteristiques-input" value="[]">

        {{-- Identification --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Identification
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="reference" class="block text-xs font-medium text-admin-600 mb-1">
                        Référence <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="reference" id="reference" required
                           value="{{ old('reference') }}"
                           placeholder="FEN-001"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('reference')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nom" class="block text-xs font-medium text-admin-600 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom') }}"
                           placeholder="Fenêtre coulissante 2 vantaux"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('nom')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Classification --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Classification
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="gamme_id" class="block text-xs font-medium text-admin-600 mb-1">Gamme</label>
                    <select name="gamme_id" id="gamme_id"
                            class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Aucune —</option>
                        @foreach($gammes as $gamme)
                            <option value="{{ $gamme->id }}" {{ old('gamme_id') == $gamme->id ? 'selected' : '' }}>
                                {{ $gamme->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="categorie_id" class="block text-xs font-medium text-admin-600 mb-1">Catégorie</label>
                    <select name="categorie_id" id="categorie_id"
                            class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Aucune —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Description
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="description_courte" class="block text-xs font-medium text-admin-600 mb-1">
                        Description courte
                    </label>
                    <textarea name="description_courte" id="description_courte" rows="2"
                              placeholder="Résumé pour le listing"
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description_courte') }}</textarea>
                </div>

                <div>
                    <label for="description_technique" class="block text-xs font-medium text-admin-600 mb-1">
                        Description technique
                    </label>
                    <textarea name="description_technique" id="description_technique" rows="4"
                              placeholder="Description complète pour la fiche"
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description_technique') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Dimensions --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Dimensions
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="largeur_min_mm" class="block text-xs font-medium text-admin-600 mb-1">Largeur min (mm)</label>
                    <input type="number" name="largeur_min_mm" id="largeur_min_mm" min="0"
                           value="{{ old('largeur_min_mm') }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="largeur_max_mm" class="block text-xs font-medium text-admin-600 mb-1">Largeur max (mm)</label>
                    <input type="number" name="largeur_max_mm" id="largeur_max_mm" min="0"
                           value="{{ old('largeur_max_mm') }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="hauteur_min_mm" class="block text-xs font-medium text-admin-600 mb-1">Hauteur min (mm)</label>
                    <input type="number" name="hauteur_min_mm" id="hauteur_min_mm" min="0"
                           value="{{ old('hauteur_min_mm') }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="hauteur_max_mm" class="block text-xs font-medium text-admin-600 mb-1">Hauteur max (mm)</label>
                    <input type="number" name="hauteur_max_mm" id="hauteur_max_mm" min="0"
                           value="{{ old('hauteur_max_mm') }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- Performances --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Performances
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="performance_thermique" class="block text-xs font-medium text-admin-600 mb-1">Thermique</label>
                    <input type="text" name="performance_thermique" id="performance_thermique"
                           value="{{ old('performance_thermique') }}"
                           placeholder="Uw = 1.8 W/m²K"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="performance_acoustique" class="block text-xs font-medium text-admin-600 mb-1">Acoustique</label>
                    <input type="text" name="performance_acoustique" id="performance_acoustique"
                           value="{{ old('performance_acoustique') }}"
                           placeholder="Rw = 38 dB"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- COMPOSITION --}}
        {{-- ============================================================ --}}
        <div class="border-t border-admin-100 pt-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                    Composition
                    <span class="text-xs text-admin-400 font-normal ml-1" id="temp-composition-count">(0)</span>
                </h2>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="openAddComposantModal()"
                            class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-xs rounded transition">
                        + Ajouter existant
                    </button>
                    <button type="button" onclick="openCreateComposantModal()"
                            class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs rounded transition">
                        + Créer nouveau
                    </button>
                </div>
            </div>

            <div id="temp-composition-list" class="divide-y divide-admin-100"></div>

            <div id="temp-composition-empty" class="text-center py-6 text-xs text-admin-400 border border-dashed border-admin-200 rounded">
                Aucun composant pour l'instant.
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- CARACTÉRISTIQUES --}}
        {{-- ============================================================ --}}
        <div class="border-t border-admin-100 pt-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                    Caractéristiques techniques
                    <span class="text-xs text-admin-400 font-normal ml-1" id="temp-caracteristiques-count">(0)</span>
                </h2>
                <button type="button" 
                        onclick="document.getElementById('form-add-carac').classList.toggle('hidden')"
                        class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-xs rounded transition">
                    + Ajouter
                </button>
            </div>

            <div id="form-add-carac" class="hidden p-4 border-b border-admin-100 bg-admin-50">
                <div class="flex items-center gap-2">
                    <input type="text" id="carac-cle" placeholder="Clé (ex: Épaisseur)"
                           class="flex-1 px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <input type="text" id="carac-valeur" placeholder="Valeur"
                           class="flex-1 px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <input type="text" id="carac-unite" placeholder="Unité"
                           class="w-24 px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <button type="button" onclick="addTempCaracteristique()"
                            class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded transition">
                        Ajouter
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('form-add-carac').classList.add('hidden')"
                            class="text-admin-400 hover:text-admin-600 text-sm">
                        ✕
                    </button>
                </div>
            </div>

            <div id="temp-caracteristiques-list" class="space-y-2"></div>

            <div id="temp-caracteristiques-empty" class="text-center py-6 text-xs text-admin-400 border border-dashed border-admin-200 rounded">
                Aucune caractéristique pour l'instant.
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- MÉDIAS --}}
        {{-- ============================================================ --}}
        <div class="border-t border-admin-100 pt-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider">
                    Schémas et images
                    <span class="text-xs text-admin-400 font-normal ml-1" id="create-preview-count-badge">(0)</span>
                </h2>
                <button type="button" 
                        onclick="document.getElementById('create-file-input').click()"
                        class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-xs rounded transition">
                    + Ajouter des images
                </button>
            </div>

            <div class="mb-3">
                <label class="block text-xs font-medium text-admin-600 mb-1">Type de média</label>
                <select name="medias_type_media" 
                        class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="schema">Schéma technique</option>
                    <option value="photo">Photo</option>
                    <option value="rendu_3d">Rendu 3D</option>
                </select>
            </div>

            <label for="create-file-input"
                   id="create-dropzone"
                   class="block border-2 border-dashed border-admin-200 rounded-lg p-6 text-center transition cursor-pointer hover:border-amber-400 hover:bg-amber-50/30">
                <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-admin-600 font-medium">Glissez vos images ici</p>
                <p class="text-xs text-admin-400 mt-1">
                    ou <span class="text-amber-600 underline font-medium">parcourez vos fichiers</span>
                </p>
                <p class="text-xs text-admin-300 mt-2">PNG, JPG — Max 5 Mo — 10 fichiers max</p>
            </label>

            <input type="file" 
                   id="create-file-input"
                   name="medias_fichiers[]" 
                   accept=".png,.jpg,.jpeg"
                   multiple
                   class="hidden">

            <div id="create-preview" class="hidden mt-3 p-3 bg-amber-50 rounded border border-amber-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-amber-800">
                        <span id="create-preview-count">0</span> image(s) sélectionnée(s)
                    </span>
                    <button type="button" id="create-clear" class="text-xs text-red-500 hover:text-red-700">
                        Tout effacer
                    </button>
                </div>
                <div id="create-preview-grid" class="grid grid-cols-4 md:grid-cols-6 gap-2"></div>
            </div>

            @error('medias_fichiers.*')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Statut --}}
        <div class="border-t border-admin-100 pt-5">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="est_actif" value="1" 
                       {{ old('est_actif', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-amber-500 border-admin-300 rounded focus:ring-amber-500">
                <span class="text-sm text-admin-700">Ouvrage actif</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-admin-100">
            <a href="{{ route('admin.ouvrages.index') }}" 
               class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                Créer l'ouvrage
            </button>
        </div>
    </form>
</div>

{{-- ============================================================ --}}
{{-- MODAL : Ajouter un composant existant --}}
{{-- ============================================================ --}}
<div id="modal-add-composant" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded shadow-xl max-w-md w-full">
        <div class="p-4 border-b border-admin-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-admin-900">Ajouter un composant existant</h3>
            <button type="button" onclick="closeModal('modal-add-composant')" class="text-admin-400 hover:text-admin-600">✕</button>
        </div>
        <div class="p-4 space-y-3">
            <div class="relative">
                <label class="block text-xs font-medium text-admin-600 mb-1">Composant</label>
                
                <input type="text" 
                       id="modal-composant-search" 
                       placeholder="Sélectionner ou rechercher un composant..."
                       autocomplete="off"
                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer">
                
                <input type="hidden" id="modal-composant-select" value="">
                
                <div id="modal-composant-results" 
                     class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-admin-200 rounded shadow-lg max-h-60 overflow-y-auto z-50">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Quantité</label>
                    <input type="number" id="modal-quantite" value="1" step="0.01" min="0.01"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                </div>
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Unité</label>
                    <select id="modal-unite" class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                        <option value="u">u</option>
                        <option value="m">m</option>
                        <option value="kg">kg</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-admin-200 flex justify-end gap-2">
            <button type="button" onclick="closeModal('modal-add-composant')"
                    class="px-4 py-2 text-sm text-admin-500">Annuler</button>
            <button type="button" onclick="addTempComposant()"
                    class="px-4 py-2 bg-admin-900 text-white text-sm rounded">Ajouter</button>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL : Créer un nouveau composant --}}
{{-- ============================================================ --}}
<div id="modal-create-composant" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
        <div class="p-4 border-b border-admin-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-admin-900">Créer un nouveau composant</h3>
            <button type="button" onclick="closeModal('modal-create-composant')" class="text-admin-400 hover:text-admin-600">✕</button>
        </div>
        <div class="p-4 space-y-3 overflow-y-auto">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Référence *</label>
                    <input type="text" id="new-composant-reference" placeholder="PRO-004-45"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                </div>
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Désignation *</label>
                    <input type="text" id="new-composant-designation" placeholder="Profilé spécial"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Type</label>
                    <select id="new-composant-type" class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                        <option value="">— Aucun —</option>
                        @foreach($typesComposant as $type)
                            <option value="{{ $type->id }}" data-slug="{{ $type->slug }}">{{ $type->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Matière</label>
                    <input type="text" id="new-composant-matiere" placeholder="Alu 6060-T6"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                </div>
            </div>

            <div id="new-section-profile" class="hidden pt-3 border-t border-admin-100 space-y-3">
                <h4 class="text-xs font-semibold text-admin-500 uppercase">Caractéristiques (profilé)</h4>
                <div class="grid grid-cols-4 gap-2">
                    <input type="number" id="new-longueur_barre_mm" placeholder="Long. 6000"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    <input type="number" id="new-section_largeur_mm" step="0.01" placeholder="Larg. 45"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    <input type="number" id="new-section_hauteur_mm" step="0.01" placeholder="Haut. 35"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    <input type="number" id="new-epaisseur_paroi_mm" step="0.01" placeholder="Ép. 1.5"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <input type="number" id="new-poids_lineaire_kg_m" step="0.001" placeholder="KG/M"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    <input type="number" id="new-poids_lineaire_lbs_ft" step="0.001" placeholder="WT/FT"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    <input type="number" id="new-moment_inertie_cm4" step="0.001" placeholder="IN"
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    <input type="number" id="new-perimetre_mm" step="0.01" placeholder="PERIM."
                           class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                </div>
            </div>

            <div class="pt-3 border-t border-admin-100">
                <h4 class="text-xs font-semibold text-admin-500 uppercase mb-2">Dans la composition</h4>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-admin-500 mb-1">Quantité</label>
                        <input type="number" id="new-composant-quantite" value="1" step="0.01" min="0.01"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                    </div>
                    <div>
                        <label class="block text-xs text-admin-500 mb-1">Unité</label>
                        <select id="new-composant-unite" class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                            <option value="u">u</option>
                            <option value="m">m</option>
                            <option value="kg">kg</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-admin-200 flex justify-end gap-2">
            <button type="button" onclick="closeModal('modal-create-composant')"
                    class="px-4 py-2 text-sm text-admin-500">Annuler</button>
            <button type="button" onclick="createTempComposant()"
                    class="px-4 py-2 bg-amber-600 text-white text-sm rounded">Créer et ajouter</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ============================================================
// ÉTAT GLOBAL
// ============================================================
window.tempComposition = [];
window.tempCaracteristiques = [];

// ============================================================
// DONNÉES COMPOSANTS
// ============================================================
window.composantsData = [
    @foreach($composantsDisponibles as $c)
    {
        id: {{ $c->id }},
        reference: @json($c->reference),
        designation: @json($c->designation),
        type: @json($c->typeComposant?->nom ?? ''),
    },
    @endforeach
];

// ============================================================
// MODALS
// ============================================================
window.openAddComposantModal = function() {
    document.getElementById('modal-add-composant').classList.remove('hidden');
    const searchInput = document.getElementById('modal-composant-search');
    const hiddenInput = document.getElementById('modal-composant-select');
    const resultsBox = document.getElementById('modal-composant-results');
    if (searchInput) searchInput.value = '';
    if (hiddenInput) hiddenInput.value = '';
    if (resultsBox) resultsBox.classList.add('hidden');
};

window.openCreateComposantModal = function() {
    document.getElementById('modal-create-composant').classList.remove('hidden');
};

window.closeModal = function(id) {
    document.getElementById(id).classList.add('hidden');
};

// ============================================================
// RECHERCHE DE COMPOSANT
// ============================================================
window.initComposantSearch = function() {
    const searchInput = document.getElementById('modal-composant-search');
    const hiddenInput = document.getElementById('modal-composant-select');
    const resultsBox = document.getElementById('modal-composant-results');

    if (!searchInput) return;

    function filterComposants(query) {
        const q = query.toLowerCase().trim();
        if (q === '') return composantsData.slice(0, 30);
        return composantsData.filter(c => 
            c.reference.toLowerCase().includes(q) || 
            c.designation.toLowerCase().includes(q) ||
            (c.type && c.type.toLowerCase().includes(q))
        ).slice(0, 30);
    }

    function renderResults(items) {
        if (items.length === 0) {
            resultsBox.innerHTML = '<div class="px-3 py-2 text-xs text-admin-400">Aucun composant trouvé</div>';
            resultsBox.classList.remove('hidden');
            return;
        }

        resultsBox.innerHTML = items.map(c => 
            '<div class="px-3 py-2 hover:bg-amber-50 cursor-pointer border-b border-admin-100 last:border-0" ' +
            'data-composant-id="' + c.id + '" ' +
            'data-composant-reference="' + c.reference.replace(/"/g, '&quot;') + '" ' +
            'data-composant-designation="' + c.designation.replace(/"/g, '&quot;') + '">' +
                '<div class="flex items-center gap-2">' +
                    '<span class="text-xs font-mono text-admin-400">' + c.reference + '</span>' +
                    '<span class="text-sm text-admin-900">' + c.designation + '</span>' +
                '</div>' +
                (c.type ? '<div class="text-xs text-admin-400 mt-0.5">' + c.type + '</div>' : '') +
            '</div>'
        ).join('');

        resultsBox.querySelectorAll('[data-composant-id]').forEach(el => {
            el.addEventListener('click', function() {
                selectComposant(
                    this.dataset.composantId,
                    this.dataset.composantReference,
                    this.dataset.composantDesignation
                );
            });
        });

        resultsBox.classList.remove('hidden');
    }

    window.selectComposant = function(id, reference, designation) {
        hiddenInput.value = id;
        searchInput.value = reference + ' — ' + designation;
        resultsBox.classList.add('hidden');
    };

    searchInput.addEventListener('input', function() {
        if (hiddenInput.value) {
            hiddenInput.value = '';
        }
        renderResults(filterComposants(this.value));
    });

    searchInput.addEventListener('focus', function() {
        renderResults(filterComposants(this.value));
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('hidden');
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') resultsBox.classList.add('hidden');
    });
};

// ============================================================
// COMPOSITION
// ============================================================
window.addTempComposant = function() {
    const id = document.getElementById('modal-composant-select').value;
    
    if (!id) {
        alert('Sélectionnez un composant.');
        return;
    }

    const composant = composantsData.find(c => c.id == id);
    if (!composant) {
        alert('Composant introuvable.');
        return;
    }

    tempComposition.push({
        composant_id: composant.id,
        reference: composant.reference,
        designation: composant.designation,
        quantite: parseFloat(document.getElementById('modal-quantite').value) || 1,
        unite: document.getElementById('modal-unite').value,
        is_new: false,
    });

    closeModal('modal-add-composant');
    
    document.getElementById('modal-composant-search').value = '';
    document.getElementById('modal-composant-select').value = '';
    document.getElementById('modal-quantite').value = '1';
    
    renderTempComposition();
};

window.createTempComposant = function() {
    const reference = document.getElementById('new-composant-reference').value.trim();
    const designation = document.getElementById('new-composant-designation').value.trim();

    if (!reference || !designation) {
        alert('Référence et désignation sont obligatoires.');
        return;
    }

    tempComposition.push({
        composant_id: null,
        reference: reference,
        designation: designation,
        type_composant_id: document.getElementById('new-composant-type').value || null,
        matiere: document.getElementById('new-composant-matiere').value || null,
        longueur_barre_mm: document.getElementById('new-longueur_barre_mm')?.value || null,
        section_largeur_mm: document.getElementById('new-section_largeur_mm')?.value || null,
        section_hauteur_mm: document.getElementById('new-section_hauteur_mm')?.value || null,
        epaisseur_paroi_mm: document.getElementById('new-epaisseur_paroi_mm')?.value || null,
        poids_lineaire_kg_m: document.getElementById('new-poids_lineaire_kg_m')?.value || null,
        poids_lineaire_lbs_ft: document.getElementById('new-poids_lineaire_lbs_ft')?.value || null,
        moment_inertie_cm4: document.getElementById('new-moment_inertie_cm4')?.value || null,
        perimetre_mm: document.getElementById('new-perimetre_mm')?.value || null,
        quantite: parseFloat(document.getElementById('new-composant-quantite').value) || 1,
        unite: document.getElementById('new-composant-unite').value,
        is_new: true,
    });

    closeModal('modal-create-composant');
    document.getElementById('new-composant-reference').value = '';
    document.getElementById('new-composant-designation').value = '';
    document.getElementById('new-composant-type').value = '';
    document.getElementById('new-composant-matiere').value = '';
    document.getElementById('new-composant-quantite').value = '1';
    renderTempComposition();
};

window.renderTempComposition = function() {
    const list = document.getElementById('temp-composition-list');
    const empty = document.getElementById('temp-composition-empty');
    const input = document.getElementById('temp-composition-input');
    const count = document.getElementById('temp-composition-count');

    if (!list) return;

    count.textContent = '(' + tempComposition.length + ')';

    if (tempComposition.length === 0) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        input.value = '[]';
        return;
    }

    empty.classList.add('hidden');
    list.innerHTML = '';

    tempComposition.forEach(function(item, index) {
        const div = document.createElement('div');
        div.className = 'p-3 hover:bg-admin-50 transition group';
        div.innerHTML = 
            '<div class="flex items-center gap-3">' +
                '<div class="flex-1 min-w-0">' +
                    '<div class="flex items-center gap-2 flex-wrap">' +
                        '<span class="text-sm font-medium text-admin-900">' + item.designation + '</span>' +
                        '<span class="text-xs text-admin-400 font-mono">' + item.reference + '</span>' +
                        (item.is_new ? '<span class="text-xs px-1.5 py-0.5 rounded bg-amber-100 text-amber-700">NOUVEAU</span>' : '') +
                    '</div>' +
                    '<div class="text-xs text-admin-500 mt-0.5">' +
                        'Quantité : <strong>' + item.quantite + '</strong> ' + item.unite +
                    '</div>' +
                '</div>' +
                '<div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">' +
                    '<button type="button" onclick="removeTempComposant(' + index + ')" class="p-1 text-red-400 hover:text-red-600 transition">' +
                        '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' +
                        '</svg>' +
                    '</button>' +
                '</div>' +
            '</div>';
        list.appendChild(div);
    });

    input.value = JSON.stringify(tempComposition);
};

window.removeTempComposant = function(index) {
    tempComposition.splice(index, 1);
    renderTempComposition();
};

// ============================================================
// CARACTÉRISTIQUES
// ============================================================
window.addTempCaracteristique = function() {
    const cle = document.getElementById('carac-cle').value.trim();
    const valeur = document.getElementById('carac-valeur').value.trim();
    const unite = document.getElementById('carac-unite').value.trim();

    if (!cle || !valeur) {
        alert('Clé et valeur sont obligatoires.');
        return;
    }

    tempCaracteristiques.push({ cle: cle, valeur: valeur, unite: unite });
    
    document.getElementById('carac-cle').value = '';
    document.getElementById('carac-valeur').value = '';
    document.getElementById('carac-unite').value = '';
    document.getElementById('carac-cle').focus();
    
    renderTempCaracteristiques();
};

window.renderTempCaracteristiques = function() {
    const list = document.getElementById('temp-caracteristiques-list');
    const empty = document.getElementById('temp-caracteristiques-empty');
    const input = document.getElementById('temp-caracteristiques-input');
    const count = document.getElementById('temp-caracteristiques-count');

    count.textContent = '(' + tempCaracteristiques.length + ')';

    if (tempCaracteristiques.length === 0) {
        list.innerHTML = '';
        empty.classList.remove('hidden');
        input.value = '[]';
        return;
    }

    empty.classList.add('hidden');
    list.innerHTML = '';

    tempCaracteristiques.forEach(function(carac, index) {
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 p-3 bg-admin-50 rounded border border-admin-100';
        div.innerHTML = 
            '<input type="text" value="' + carac.cle + '" placeholder="Clé" ' +
                'onchange="updateTempCarac(' + index + ', \'cle\', this.value)" ' +
                'class="flex-1 px-2 py-1 text-sm border border-transparent hover:border-admin-200 focus:border-amber-500 rounded bg-transparent">' +
            '<input type="text" value="' + carac.valeur + '" placeholder="Valeur" ' +
                'onchange="updateTempCarac(' + index + ', \'valeur\', this.value)" ' +
                'class="flex-1 px-2 py-1 text-sm border border-transparent hover:border-admin-200 focus:border-amber-500 rounded bg-transparent">' +
            '<input type="text" value="' + carac.unite + '" placeholder="Unité" ' +
                'onchange="updateTempCarac(' + index + ', \'unite\', this.value)" ' +
                'class="w-24 px-2 py-1 text-sm border border-transparent hover:border-admin-200 focus:border-amber-500 rounded bg-transparent">' +
            '<button type="button" onclick="removeTempCaracteristique(' + index + ')" class="p-1 text-red-400 hover:text-red-600">' +
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' +
                '</svg>' +
            '</button>';
        list.appendChild(div);
    });

    input.value = JSON.stringify(tempCaracteristiques);
};

window.updateTempCarac = function(index, field, value) {
    tempCaracteristiques[index][field] = value;
    document.getElementById('temp-caracteristiques-input').value = JSON.stringify(tempCaracteristiques);
};

window.removeTempCaracteristique = function(index) {
    tempCaracteristiques.splice(index, 1);
    renderTempCaracteristiques();
};

// ============================================================
// INIT AU CHARGEMENT
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    initComposantSearch();

    // Conditionnel profilé
    const typeSelect = document.getElementById('new-composant-type');
    const sectionProfile = document.getElementById('new-section-profile');

    if (typeSelect && sectionProfile) {
        typeSelect.addEventListener('change', function() {
            const slug = this.options[this.selectedIndex]?.dataset?.slug;
            if (slug === 'profile' || slug === 'profil') {
                sectionProfile.classList.remove('hidden');
            } else {
                sectionProfile.classList.add('hidden');
            }
        });
    }

    // Upload images
    const dropzone = document.getElementById('create-dropzone');
    const fileInput = document.getElementById('create-file-input');
    const preview = document.getElementById('create-preview');
    const previewGrid = document.getElementById('create-preview-grid');
    const previewCount = document.getElementById('create-preview-count');
    const previewCountBadge = document.getElementById('create-preview-count-badge');
    const clearBtn = document.getElementById('create-clear');

    if (!dropzone || !fileInput) return;

    let pendingFiles = [];

    fileInput.addEventListener('change', function(e) {
        addFiles(Array.from(e.target.files));
    });

    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.add('border-amber-400', 'bg-amber-50');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('border-amber-400', 'bg-amber-50');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        dropzone.classList.remove('border-amber-400', 'bg-amber-50');
        const files = Array.from(e.dataTransfer.files).filter(f =>
            f.type === 'image/png' || f.type === 'image/jpeg'
        );
        addFiles(files);
    });

    function addFiles(files) {
        const total = pendingFiles.length + files.length;
        if (total > 10) {
            alert('Maximum 10 fichiers.');
            files = files.slice(0, 10 - pendingFiles.length);
        }
        pendingFiles = pendingFiles.concat(files);
        renderPreview();
    }

    function renderPreview() {
        if (previewCountBadge) previewCountBadge.textContent = '(' + pendingFiles.length + ')';

        if (pendingFiles.length === 0) {
            preview.classList.add('hidden');
            return;
        }

        preview.classList.remove('hidden');
        previewCount.textContent = pendingFiles.length;
        previewGrid.innerHTML = '';

        const dt = new DataTransfer();
        pendingFiles.forEach(function(f) { dt.items.add(f); });
        fileInput.files = dt.files;

        pendingFiles.forEach(function(file, index) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative aspect-square bg-white rounded border border-admin-200 overflow-hidden group';
                div.innerHTML =
                    '<img src="' + e.target.result + '" class="w-full h-full object-contain">' +
                    '<button type="button" data-remove="' + index + '" ' +
                    'class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition">' +
                    '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>' +
                    '</svg></button>';
                previewGrid.appendChild(div);

                div.querySelector('[data-remove]').addEventListener('click', function() {
                    pendingFiles.splice(index, 1);
                    renderPreview();
                });
            };
            reader.readAsDataURL(file);
        });
    }

    clearBtn.addEventListener('click', function() {
        if (confirm('Effacer toutes les images ?')) {
            pendingFiles = [];
            fileInput.value = '';
            renderPreview();
        }
    });
});
</script>
@endpush