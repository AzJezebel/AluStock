{{-- resources/views/admin/composants/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Éditer - ' . $composant->designation)

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">{{ $composant->designation }}</h1>
            <p class="text-xs text-admin-500 mt-0.5">
                Réf. <span class="font-mono">{{ $composant->reference }}</span>
                @if($composant->typeComposant) — {{ $composant->typeComposant->nom }} @endif
                @if($composant->gamme) — {{ $composant->gamme->nom }} @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('composants.show', $composant) }}" 
               target="_blank"
               class="text-xs text-admin-500 hover:text-admin-700">
                Voir sur le site ↗
            </a>
            <a href="{{ route('admin.composants.index') }}" 
               class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
        </div>
    </div>

    {{-- Formulaire --}}
    <form action="{{ route('admin.composants.update', $composant) }}" method="POST" 
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-4xl">
        @csrf
        @method('PUT')

        {{-- Section : Identification --}}
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
                           value="{{ old('reference', $composant->reference) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('reference')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="designation" class="block text-xs font-medium text-admin-600 mb-1">
                        Désignation <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="designation" id="designation" required
                           value="{{ old('designation', $composant->designation) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('designation')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Section : Classification --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Classification
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="type_composant_id" class="block text-xs font-medium text-admin-600 mb-1">
                        Type de composant
                    </label>
                    <select name="type_composant_id" id="type_composant_id"
                            class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Sélectionner —</option>
                        @foreach($typesComposant as $type)
                            <option value="{{ $type->id }}" 
                                    data-slug="{{ $type->slug }}"
                                    {{ old('type_composant_id', $composant->type_composant_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="gamme_id" class="block text-xs font-medium text-admin-600 mb-1">Gamme</label>
                    <select name="gamme_id" id="gamme_id"
                            class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Aucune —</option>
                        @foreach($gammes as $gamme)
                            <option value="{{ $gamme->id }}" {{ old('gamme_id', $composant->gamme_id) == $gamme->id ? 'selected' : '' }}>
                                {{ $gamme->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label for="matiere" class="block text-xs font-medium text-admin-600 mb-1">Matière</label>
                <input type="text" name="matiere" id="matiere"
                       value="{{ old('matiere', $composant->matiere) }}"
                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- SECTION CONDITIONNELLE : Champs profilés --}}
        {{-- ============================================================ --}}
        <div id="section-profile" class="hidden">
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Caractéristiques techniques (profilé)
            </h2>

            {{-- Dimensions --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="longueur_barre_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Longueur barre (mm)
                    </label>
                    <input type="number" name="longueur_barre_mm" id="longueur_barre_mm" min="0"
                           value="{{ old('longueur_barre_mm', $composant->longueur_barre_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="section_largeur_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Largeur section (mm)
                    </label>
                    <input type="number" name="section_largeur_mm" id="section_largeur_mm" step="0.01" min="0"
                           value="{{ old('section_largeur_mm', $composant->section_largeur_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="section_hauteur_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Hauteur section (mm)
                    </label>
                    <input type="number" name="section_hauteur_mm" id="section_hauteur_mm" step="0.01" min="0"
                           value="{{ old('section_hauteur_mm', $composant->section_hauteur_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="epaisseur_paroi_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Épaisseur paroi (mm)
                    </label>
                    <input type="number" name="epaisseur_paroi_mm" id="epaisseur_paroi_mm" step="0.01" min="0"
                           value="{{ old('epaisseur_paroi_mm', $composant->epaisseur_paroi_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            {{-- Poids linéaire --}}
            <div class="p-3 bg-admin-50 rounded border border-admin-100 mb-4">
                <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                    Poids linéaire
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="poids_lineaire_kg_m" class="block text-xs text-admin-500 mb-1">
                            KG/M <span class="text-admin-400">(métrique)</span>
                        </label>
                        <input type="number" name="poids_lineaire_kg_m" id="poids_lineaire_kg_m" step="0.001" min="0"
                               value="{{ old('poids_lineaire_kg_m', $composant->poids_lineaire_kg_m) }}"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label for="poids_lineaire_lbs_ft" class="block text-xs text-admin-500 mb-1">
                            WT/FT <span class="text-admin-400">(impérial)</span>
                        </label>
                        <input type="number" name="poids_lineaire_lbs_ft" id="poids_lineaire_lbs_ft" step="0.001" min="0"
                               value="{{ old('poids_lineaire_lbs_ft', $composant->poids_lineaire_lbs_ft) }}"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            {{-- Inertie + Périmètre --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-3 bg-admin-50 rounded border border-admin-100">
                    <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                        Moment d'inertie
                    </h3>
                    <div>
                        <label for="moment_inertie_cm4" class="block text-xs text-admin-500 mb-1">
                            IN (cm⁴)
                        </label>
                        <input type="number" name="moment_inertie_cm4" id="moment_inertie_cm4" step="0.01" min="0"
                               value="{{ old('moment_inertie_cm4', $composant->moment_inertie_cm4) }}"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="p-3 bg-admin-50 rounded border border-admin-100">
                    <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                        Périmètre
                    </h3>
                    <div>
                        <label for="perimetre_mm" class="block text-xs text-admin-500 mb-1">
                            PERIM. (mm)
                        </label>
                        <input type="number" name="perimetre_mm" id="perimetre_mm" step="0.01" min="0"
                               value="{{ old('perimetre_mm', $composant->perimetre_mm) }}"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section : Statut --}}
        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="est_disponible" value="1" 
                       {{ old('est_disponible', $composant->est_disponible) ? 'checked' : '' }}
                       class="w-4 h-4 text-amber-500 border-admin-300 rounded focus:ring-amber-500">
                <span class="text-sm text-admin-700">Composant disponible</span>
            </label>
        </div>

        {{-- Utilisé dans --}}
        @if($composant->ouvrages->count() > 0)
            <div class="pt-4 border-t border-admin-100">
                <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                    Utilisé dans {{ $composant->ouvrages->count() }} ouvrage(s)
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($composant->ouvrages as $ouvrage)
                        <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" 
                           class="inline-flex items-center px-2 py-1 rounded text-xs bg-admin-100 text-admin-600 hover:bg-admin-200 transition">
                            {{ $ouvrage->nom }}
                            <span class="text-admin-400 ml-1">({{ $ouvrage->pivot->quantite }} {{ $ouvrage->pivot->unite }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-admin-100">
            <form action="{{ route('admin.composants.destroy', $composant) }}" method="POST" 
                  onsubmit="return confirm('Supprimer ce composant ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">
                    Supprimer ce composant
                </button>
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.composants.index') }}" 
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
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type_composant_id');
        const sectionProfile = document.getElementById('section-profile');

        function updateSections() {
            const selectedOption = typeSelect.options[typeSelect.selectedIndex];
            const slug = selectedOption?.dataset?.slug;

            if (slug === 'profile' || slug === 'profil') {
                sectionProfile.classList.remove('hidden');
            } else {
                sectionProfile.classList.add('hidden');
            }
        }

        typeSelect.addEventListener('change', updateSections);
        updateSections();

        // Conversions automatiques
        const kgInput = document.getElementById('poids_lineaire_kg_m');
        const wtInput = document.getElementById('poids_lineaire_lbs_ft');

        if (kgInput && wtInput) {
            kgInput.addEventListener('input', function() {
                if (this.value && !wtInput.dataset.touched) {
                    wtInput.value = (parseFloat(this.value) * 0.672).toFixed(3);
                }
                kgInput.dataset.touched = 'true';
            });

            wtInput.addEventListener('input', function() {
                if (this.value && !kgInput.dataset.touched) {
                    kgInput.value = (parseFloat(this.value) / 0.672).toFixed(3);
                }
                wtInput.dataset.touched = 'true';
            });
        }
    });
</script>
@endpush
@endsection