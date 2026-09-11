{{-- resources/views/admin/composants/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nouveau composant - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Nouveau composant</h1>
            <p class="text-xs text-admin-500 mt-0.5">Créer un nouveau composant</p>
        </div>
        <a href="{{ route('admin.composants.index') }}" 
           class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
    </div>

    {{-- Formulaire --}}
    <form action="{{ route('admin.composants.store') }}" method="POST" 
          enctype="multipart/form-data"
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-4xl">
        @csrf

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
                           placeholder="PRO-001-45"
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
                           value="{{ old('designation') }}"
                           placeholder="Rail haut 45mm"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('designation')
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
                    <label for="type_composant_id" class="block text-xs font-medium text-admin-600 mb-1">
                        Type de composant
                    </label>
                    <select name="type_composant_id" id="type_composant_id"
                            class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Sélectionner —</option>
                        @foreach($typesComposant as $type)
                            <option value="{{ $type->id }}" 
                                    data-slug="{{ $type->slug }}"
                                    {{ old('type_composant_id') == $type->id ? 'selected' : '' }}>
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
                            <option value="{{ $gamme->id }}" {{ old('gamme_id') == $gamme->id ? 'selected' : '' }}>
                                {{ $gamme->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label for="matiere" class="block text-xs font-medium text-admin-600 mb-1">Matière</label>
                <input type="text" name="matiere" id="matiere"
                       value="{{ old('matiere') }}"
                       placeholder="Alu 6060-T6"
                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
        </div>

        {{-- Section conditionnelle : Champs profilés --}}
        <div id="section-profile" class="hidden">
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Caractéristiques techniques (profilé)
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label for="longueur_barre_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Longueur barre (mm)
                    </label>
                    <input type="number" name="longueur_barre_mm" id="longueur_barre_mm" min="0"
                           value="{{ old('longueur_barre_mm') }}"
                           placeholder="6000"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="section_largeur_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Largeur section (mm)
                    </label>
                    <input type="number" name="section_largeur_mm" id="section_largeur_mm" step="0.01" min="0"
                           value="{{ old('section_largeur_mm') }}"
                           placeholder="45.00"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="section_hauteur_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Hauteur section (mm)
                    </label>
                    <input type="number" name="section_hauteur_mm" id="section_hauteur_mm" step="0.01" min="0"
                           value="{{ old('section_hauteur_mm') }}"
                           placeholder="35.00"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="epaisseur_paroi_mm" class="block text-xs font-medium text-admin-600 mb-1">
                        Épaisseur paroi (mm)
                    </label>
                    <input type="number" name="epaisseur_paroi_mm" id="epaisseur_paroi_mm" step="0.01" min="0"
                           value="{{ old('epaisseur_paroi_mm') }}"
                           placeholder="1.50"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            {{-- Poids linéaire --}}
            <div class="p-3 bg-admin-50 rounded border border-admin-100 mb-4">
                <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">Poids linéaire</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="poids_lineaire_kg_m" class="block text-xs text-admin-500 mb-1">KG/M</label>
                        <input type="number" name="poids_lineaire_kg_m" id="poids_lineaire_kg_m" step="0.001" min="0"
                               value="{{ old('poids_lineaire_kg_m') }}"
                               placeholder="2.450"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label for="poids_lineaire_lbs_ft" class="block text-xs text-admin-500 mb-1">WT/FT</label>
                        <input type="number" name="poids_lineaire_lbs_ft" id="poids_lineaire_lbs_ft" step="0.001" min="0"
                               value="{{ old('poids_lineaire_lbs_ft') }}"
                               placeholder="1.647"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            {{-- Inertie + Périmètre --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-3 bg-admin-50 rounded border border-admin-100">
                    <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">Inertie</h3>
                    <div>
                        <label for="moment_inertie_cm4" class="block text-xs text-admin-500 mb-1">IN (cm⁴)</label>
                        <input type="number" name="moment_inertie_cm4" id="moment_inertie_cm4" step="0.01" min="0"
                               value="{{ old('moment_inertie_cm4') }}"
                               placeholder="85.30"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="p-3 bg-admin-50 rounded border border-admin-100">
                    <h3 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">Périmètre</h3>
                    <div>
                        <label for="perimetre_mm" class="block text-xs text-admin-500 mb-1">PERIM. (mm)</label>
                        <input type="number" name="perimetre_mm" id="perimetre_mm" step="0.01" min="0"
                               value="{{ old('perimetre_mm') }}"
                               placeholder="205.39"
                               class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>
        </div>

        {{-- Médias --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Schémas et images
            </h2>

            <div class="mb-3">
                <label class="block text-xs font-medium text-admin-600 mb-1">Type de média</label>
                <select name="medias_type_media" 
                        class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <option value="schema">Schéma technique</option>
                    <option value="photo">Photo</option>
                    <option value="rendu_3d">Rendu 3D</option>
                </select>
            </div>

            {{-- Zone de drop custom --}}
            <div data-create-dropzone
                 class="border-2 border-dashed border-admin-200 rounded-lg p-6 text-center transition cursor-pointer hover:border-amber-400 hover:bg-amber-50/30">
                <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-admin-600 font-medium">Glissez vos images ici</p>
                <p class="text-xs text-admin-400 mt-1">
                    ou <span class="text-amber-600 underline font-medium">parcourez vos fichiers</span>
                </p>
                <p class="text-xs text-admin-300 mt-2">PNG, JPG — Max 5 Mo — 10 fichiers max</p>
            </div>

            {{-- Input caché --}}
            <input type="file" 
                   name="medias_fichiers[]" 
                   data-create-file-input
                   accept=".png,.jpg,.jpeg"
                   multiple
                   class="hidden">

            {{-- Aperçu --}}
            <div data-create-preview class="hidden mt-3 p-3 bg-amber-50 rounded border border-amber-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-amber-800">
                        <span data-create-preview-count>0</span> image(s) sélectionnée(s)
                    </span>
                    <button type="button" data-create-clear class="text-xs text-red-500 hover:text-red-700">
                        Tout effacer
                    </button>
                </div>
                <div data-create-preview-grid class="grid grid-cols-4 md:grid-cols-6 gap-2"></div>
            </div>

            @error('medias_fichiers.*')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Statut --}}
        <div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="est_disponible" value="1" 
                       {{ old('est_disponible', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-amber-500 border-admin-300 rounded focus:ring-amber-500">
                <span class="text-sm text-admin-700">Composant disponible</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-admin-100">
            <a href="{{ route('admin.composants.index') }}" 
               class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                Créer le composant
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================================
        // CONDITIONNEL PROFILÉ
        // ============================================================
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

        // ============================================================
        // UPLOAD IMAGES
        // ============================================================
        const dropzone = document.querySelector('[data-create-dropzone]');
        const fileInput = document.querySelector('[data-create-file-input]');
        const preview = document.querySelector('[data-create-preview]');
        const previewGrid = document.querySelector('[data-create-preview-grid]');
        const previewCount = document.querySelector('[data-create-preview-count]');
        const clearBtn = document.querySelector('[data-create-clear]');

        if (!dropzone) return;

        let pendingFiles = [];

        dropzone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (e) => {
            addFiles(Array.from(e.target.files));
        });

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-amber-400', 'bg-amber-50');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-amber-400', 'bg-amber-50');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
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
            pendingFiles = [...pendingFiles, ...files];
            renderPreview();
        }

        function renderPreview() {
            if (pendingFiles.length === 0) {
                preview.classList.add('hidden');
                fileInput.files = new DataTransfer().files;
                return;
            }

            preview.classList.remove('hidden');
            previewCount.textContent = pendingFiles.length;
            previewGrid.innerHTML = '';

            const dt = new DataTransfer();
            pendingFiles.forEach(f => dt.items.add(f));
            fileInput.files = dt.files;

            pendingFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square bg-white rounded border border-admin-200 overflow-hidden group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-contain">
                        <button type="button" data-remove="${index}"
                                class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    previewGrid.appendChild(div);
                    div.querySelector('[data-remove]').addEventListener('click', () => {
                        pendingFiles.splice(index, 1);
                        renderPreview();
                    });
                };
                reader.readAsDataURL(file);
            });
        }

        clearBtn.addEventListener('click', () => {
            if (confirm('Effacer toutes les images ?')) {
                pendingFiles = [];
                renderPreview();
            }
        });
    });
</script>
@endpush
@endsection