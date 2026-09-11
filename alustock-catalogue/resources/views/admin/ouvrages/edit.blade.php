{{-- resources/views/admin/ouvrages/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Éditer - ' . $ouvrage->nom)

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">{{ $ouvrage->nom }}</h1>
            <p class="text-xs text-admin-500 mt-0.5">
                Réf. <span class="font-mono">{{ $ouvrage->reference }}</span>
                @if($ouvrage->gamme) — {{ $ouvrage->gamme->nom }} @endif
                @if($ouvrage->categorie) — {{ $ouvrage->categorie->nom }} @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('ouvrages.show', $ouvrage) }}" 
               target="_blank"
               class="text-xs text-admin-500 hover:text-admin-700">Voir sur le site ↗</a>
            <a href="{{ route('admin.ouvrages.index') }}" 
               class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FORMULAIRE PRINCIPAL (infos de l'ouvrage) --}}
    {{-- ============================================================ --}}
    <form action="{{ route('admin.ouvrages.update', $ouvrage) }}" method="POST" 
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-4xl mb-6">
        @csrf
        @method('PUT')

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
                           value="{{ old('reference', $ouvrage->reference) }}"
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
                           value="{{ old('nom', $ouvrage->nom) }}"
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
                            <option value="{{ $gamme->id }}" {{ old('gamme_id', $ouvrage->gamme_id) == $gamme->id ? 'selected' : '' }}>
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
                            <option value="{{ $cat->id }}" {{ old('categorie_id', $ouvrage->categorie_id) == $cat->id ? 'selected' : '' }}>
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
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description_courte', $ouvrage->description_courte) }}</textarea>
                </div>

                <div>
                    <label for="description_technique" class="block text-xs font-medium text-admin-600 mb-1">
                        Description technique
                    </label>
                    <textarea name="description_technique" id="description_technique" rows="4"
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description_technique', $ouvrage->description_technique) }}</textarea>
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
                           value="{{ old('largeur_min_mm', $ouvrage->largeur_min_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="largeur_max_mm" class="block text-xs font-medium text-admin-600 mb-1">Largeur max (mm)</label>
                    <input type="number" name="largeur_max_mm" id="largeur_max_mm" min="0"
                           value="{{ old('largeur_max_mm', $ouvrage->largeur_max_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="hauteur_min_mm" class="block text-xs font-medium text-admin-600 mb-1">Hauteur min (mm)</label>
                    <input type="number" name="hauteur_min_mm" id="hauteur_min_mm" min="0"
                           value="{{ old('hauteur_min_mm', $ouvrage->hauteur_min_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="hauteur_max_mm" class="block text-xs font-medium text-admin-600 mb-1">Hauteur max (mm)</label>
                    <input type="number" name="hauteur_max_mm" id="hauteur_max_mm" min="0"
                           value="{{ old('hauteur_max_mm', $ouvrage->hauteur_max_mm) }}"
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
                           value="{{ old('performance_thermique', $ouvrage->performance_thermique) }}"
                           placeholder="Uw = 1.8 W/m²K"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="performance_acoustique" class="block text-xs font-medium text-admin-600 mb-1">Acoustique</label>
                    <input type="text" name="performance_acoustique" id="performance_acoustique"
                           value="{{ old('performance_acoustique', $ouvrage->performance_acoustique) }}"
                           placeholder="Rw = 38 dB"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- Statut --}}
        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="est_actif" value="1" 
                       {{ old('est_actif', $ouvrage->est_actif) ? 'checked' : '' }}
                       class="w-4 h-4 text-amber-500 border-admin-300 rounded focus:ring-amber-500">
                <span class="text-sm text-admin-700">Ouvrage actif</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-admin-100">
            <button type="button" 
                    onclick="if(confirm('Supprimer cet ouvrage ?')) document.getElementById('delete-ouvrage-form').submit();"
                    class="text-xs text-red-400 hover:text-red-600 transition">
                Supprimer cet ouvrage
            </button>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.ouvrages.index') }}" 
                   class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>

    {{-- Formulaire suppression (hors du formulaire principal) --}}
    <form id="delete-ouvrage-form" action="{{ route('admin.ouvrages.destroy', $ouvrage) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- ============================================================ --}}
    {{-- COMPOSITION (sous-ressource) --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded border border-admin-200 mb-6 max-w-4xl">
        <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-admin-700">
                Composition
                <span class="text-xs text-admin-400 font-normal ml-1">({{ $ouvrage->composants->count() }})</span>
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

        <div id="composition-list" class="divide-y divide-admin-100">
            @forelse($ouvrage->composants->sortBy('pivot.ordre') as $composant)
                <div class="p-3 hover:bg-admin-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-medium text-admin-900">{{ $composant->designation }}</span>
                                <span class="text-xs text-admin-400 font-mono">{{ $composant->reference }}</span>
                                @if($composant->typeComposant)
                                    <span class="text-xs px-1.5 py-0.5 rounded bg-admin-100 text-admin-500">
                                        {{ $composant->typeComposant->nom }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-admin-500 mt-0.5">
                                Quantité : <strong>{{ $composant->pivot->quantite }}</strong> {{ $composant->pivot->unite }}
                                @if($composant->pivot->commentaire)
                                    — <span class="italic">{{ $composant->pivot->commentaire }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                            <a href="{{ route('admin.composants.edit', $composant) }}" 
                               target="_blank"
                               class="p-1 text-admin-400 hover:text-admin-700 transition"
                               title="Éditer le composant">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('admin.ouvrages.composition.destroy', [$ouvrage, $composant]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Retirer ce composant ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-red-400 hover:text-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-admin-400">
                    Aucun composant dans la composition.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- CARACTÉRISTIQUES TECHNIQUES (EAV) --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded border border-admin-200 mb-6 max-w-4xl">
        <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-admin-700">
                Caractéristiques techniques
                <span class="text-xs text-admin-400 font-normal ml-1">({{ $ouvrage->caracteristiques->count() }})</span>
            </h2>
            <button type="button" 
                    onclick="document.getElementById('form-add-carac').classList.toggle('hidden')"
                    class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-xs rounded transition">
                + Ajouter
            </button>
        </div>

        {{-- Formulaire d'ajout inline --}}
        <div id="form-add-carac" class="hidden p-4 border-b border-admin-100 bg-admin-50">
            <form action="{{ route('admin.ouvrages.caracteristiques.store', $ouvrage) }}" method="POST">
                @csrf
                <div class="flex items-center gap-2">
                    <input type="text" name="cle" placeholder="Clé (ex: Épaisseur)" required
                           class="flex-1 px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <input type="text" name="valeur" placeholder="Valeur" required
                           class="flex-1 px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <input type="text" name="unite" placeholder="Unité"
                           class="w-24 px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <button type="submit"
                            class="px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded transition">
                        Ajouter
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('form-add-carac').classList.add('hidden')"
                            class="text-admin-400 hover:text-admin-600 text-sm">
                        ✕
                    </button>
                </div>
            </form>
        </div>

        {{-- Liste des caractéristiques --}}
        <div class="divide-y divide-admin-100">
            @forelse($ouvrage->caracteristiques->sortBy('ordre_affichage') as $carac)
                <div class="p-3 hover:bg-admin-50 transition group">
                    <form action="{{ route('admin.ouvrages.caracteristiques.update', [$ouvrage, $carac]) }}" 
                          method="POST" class="flex items-center gap-2">
                        @csrf
                        @method('PUT')
                        <input type="text" name="cle" value="{{ $carac->cle }}" required
                               class="flex-1 px-2 py-1 text-sm border border-transparent hover:border-admin-200 focus:border-amber-500 rounded focus:outline-none focus:ring-1 focus:ring-amber-500 bg-transparent">
                        <input type="text" name="valeur" value="{{ $carac->valeur }}" required
                               class="flex-1 px-2 py-1 text-sm border border-transparent hover:border-admin-200 focus:border-amber-500 rounded focus:outline-none focus:ring-1 focus:ring-amber-500 bg-transparent">
                        <input type="text" name="unite" value="{{ $carac->unite }}"
                               placeholder="Unité"
                               class="w-24 px-2 py-1 text-sm border border-transparent hover:border-admin-200 focus:border-amber-500 rounded focus:outline-none focus:ring-1 focus:ring-amber-500 bg-transparent">
                        
                        <button type="submit" 
                                class="opacity-0 group-hover:opacity-100 text-xs text-admin-500 hover:text-admin-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                    </form>
                    <form action="{{ route('admin.ouvrages.caracteristiques.destroy', [$ouvrage, $carac]) }}" 
                          method="POST" class="inline absolute right-4"
                          onsubmit="return confirm('Supprimer cette caractéristique ?')">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            @empty
                <div class="p-8 text-center text-sm text-admin-400">
                    Aucune caractéristique.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MÉDIAS (partial existant) --}}
    {{-- ============================================================ --}}
    <div class="max-w-4xl">
        @include('admin.partials.medias', [
            'entity' => $ouvrage,
            'entityType' => 'ouvrage',
        ])
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL : Ajouter un composant existant --}}
{{-- ============================================================ --}}
<div id="modal-add-composant" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded shadow-xl max-w-md w-full">
        <form action="{{ route('admin.ouvrages.composition.store', $ouvrage) }}" method="POST">
            @csrf
            <div class="p-4 border-b border-admin-200 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-admin-900">Ajouter un composant existant</h3>
                <button type="button" onclick="closeModal('modal-add-composant')" class="text-admin-400 hover:text-admin-600">✕</button>
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Composant</label>
                    <select name="composant_id" required class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                        <option value="">Sélectionner un composant</option>
                        @foreach($composantsDisponibles as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->reference }} — {{ $c->designation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Quantité</label>
                        <input type="number" name="quantite" value="1" step="0.01" min="0.01" required
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Unité</label>
                        <select name="unite" class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                            <option value="u">u</option>
                            <option value="m">m</option>
                            <option value="kg">kg</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Commentaire</label>
                    <textarea name="commentaire" rows="2" 
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded"></textarea>
                </div>
            </div>
            <div class="p-4 border-t border-admin-200 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-add-composant')"
                        class="px-4 py-2 text-sm text-admin-500">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-admin-900 text-white text-sm rounded">Ajouter</button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL : Créer un nouveau composant --}}
{{-- ============================================================ --}}
<div id="modal-create-composant" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
        <form action="{{ route('admin.ouvrages.composition.store-new', $ouvrage) }}" method="POST">
            @csrf
            <div class="p-4 border-b border-admin-200 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-admin-900">Créer un nouveau composant</h3>
                <button type="button" onclick="closeModal('modal-create-composant')" class="text-admin-400 hover:text-admin-600">✕</button>
            </div>
            <div class="p-4 space-y-3 overflow-y-auto">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Référence *</label>
                        <input type="text" name="reference" placeholder="PRO-004-45" required
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Désignation *</label>
                        <input type="text" name="designation" placeholder="Profilé spécial" required
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Type</label>
                        <select name="type_composant_id" id="new-type-composant" 
                                class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                            <option value="">— Aucun —</option>
                            @foreach($typesComposant as $type)
                                <option value="{{ $type->id }}" data-slug="{{ $type->slug }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Matière</label>
                        <input type="text" name="matiere" placeholder="Alu 6060-T6"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded">
                    </div>
                </div>

                {{-- Champs profilés (conditionnel) --}}
                <div id="new-section-profile" class="hidden pt-3 border-t border-admin-100 space-y-3">
                    <h4 class="text-xs font-semibold text-admin-500 uppercase">Caractéristiques (profilé)</h4>
                    <div class="grid grid-cols-4 gap-2">
                        <input type="number" name="longueur_barre_mm" placeholder="Long. 6000"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                        <input type="number" name="section_largeur_mm" step="0.01" placeholder="Larg. 45"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                        <input type="number" name="section_hauteur_mm" step="0.01" placeholder="Haut. 35"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                        <input type="number" name="epaisseur_paroi_mm" step="0.01" placeholder="Ép. 1.5"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <input type="number" name="poids_lineaire_kg_m" step="0.001" placeholder="KG/M"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                        <input type="number" name="poids_lineaire_lbs_ft" step="0.001" placeholder="WT/FT"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                        <input type="number" name="moment_inertie_cm4" step="0.01" placeholder="IN"
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                        <input type="number" name="perimetre_mm" step="0.01" placeholder="PERIM."
                               class="px-2 py-1.5 text-xs border border-admin-200 rounded">
                    </div>
                </div>

                <div class="pt-3 border-t border-admin-100">
                    <h4 class="text-xs font-semibold text-admin-500 uppercase mb-2">Dans la composition</h4>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="number" name="quantite" value="1" step="0.01" min="0.01" required
                               class="px-3 py-2 text-sm border border-admin-200 rounded">
                        <select name="unite" class="px-3 py-2 text-sm border border-admin-200 rounded">
                            <option value="u">u</option>
                            <option value="m">m</option>
                            <option value="kg">kg</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-admin-200 flex justify-end gap-2">
                <button type="button" onclick="closeModal('modal-create-composant')"
                        class="px-4 py-2 text-sm text-admin-500">Annuler</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm rounded">Créer et ajouter</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddComposantModal() {
    document.getElementById('modal-add-composant').classList.remove('hidden');
}

function openCreateComposantModal() {
    document.getElementById('modal-create-composant').classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// Affichage conditionnel des champs profilés
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('new-type-composant');
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
});
</script>
@endpush
@endsection