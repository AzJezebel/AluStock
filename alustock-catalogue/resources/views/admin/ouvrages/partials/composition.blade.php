{{-- resources/views/admin/ouvrages/partials/composition.blade.php --}}
<div class="bg-white rounded border border-admin-200">
    <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-admin-700">
            Composition
            <span class="text-xs text-admin-400 font-normal ml-1">({{ $ouvrage->composants->count() }})</span>
        </h2>
        <button type="button" 
                onclick="openCompositionModal()"
                class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-xs rounded transition">
            + Ajouter un composant
        </button>
    </div>

    {{-- Liste des composants --}}
    <div id="composition-list" class="divide-y divide-admin-100">
        @forelse($ouvrage->composants as $composant)
            <div class="p-3 hover:bg-admin-50 transition group" data-id="{{ $composant->id }}">
                <div class="flex items-center gap-3">
                    
                    {{-- Drag handle --}}
                    <div class="cursor-move text-admin-300 hover:text-admin-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-medium text-admin-900">{{ $composant->designation }}</span>
                            <span class="text-xs text-admin-400 font-mono">{{ $composant->reference }}</span>
                            @if($composant->typeComposant)
                                <span class="text-xs px-1.5 py-0.5 rounded bg-admin-100 text-admin-500">
                                    {{ $composant->typeComposant->nom }}
                                </span>
                            @endif
                            @if($composant->gamme)
                                <span class="text-xs px-1.5 py-0.5 rounded bg-amber-50 text-amber-700">
                                    {{ $composant->gamme->nom }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-admin-500 mt-0.5 flex items-center gap-3 flex-wrap">
                            <span>Quantité : <strong>{{ $composant->pivot->quantite }}</strong> {{ $composant->pivot->unite }}</span>
                            @if($composant->poids_lineaire_kg_m)
                                <span>KG/M : <strong>{{ $composant->poids_lineaire_kg_m }}</strong></span>
                            @endif
                            @if($composant->poids_lineaire_lbs_ft)
                                <span>WT/FT : <strong>{{ $composant->poids_lineaire_lbs_ft }}</strong></span>
                            @endif
                            @if($composant->moment_inertie_cm4)
                                <span>IN : <strong>{{ $composant->moment_inertie_cm4 }}</strong></span>
                            @endif
                            @if($composant->perimetre_mm)
                                <span>PERIM : <strong>{{ $composant->perimetre_mm }}</strong></span>
                            @endif
                        </div>
                        @if($composant->pivot->commentaire)
                            <div class="text-xs text-admin-400 mt-0.5 italic">
                                {{ $composant->pivot->commentaire }}
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
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
                              class="inline"
                              onsubmit="return confirm('Retirer ce composant de la composition ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1 text-red-400 hover:text-red-600 transition"
                                    title="Retirer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-sm text-admin-400">
                <svg class="w-8 h-8 mx-auto mb-2 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Aucun composant dans la composition.
                <button type="button" 
                        onclick="openCompositionModal()"
                        class="text-admin-600 hover:text-admin-800 underline ml-1">
                    Ajouter le premier
                </button>
            </div>
        @endforelse
    </div>
</div>

{{-- ============================================================
     MODAL : Ajouter un composant
     ============================================================ --}}
<div id="modal-composition" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
        
        {{-- En-tête avec onglets --}}
        <div class="border-b border-admin-200 flex-shrink-0">
            <div class="flex">
                <button type="button" 
                        onclick="switchCompositionTab('existing')"
                        id="tab-existing"
                        class="flex-1 px-4 py-3 text-sm font-medium border-b-2 border-amber-500 text-amber-700 transition">
                    Composant existant
                </button>
                <button type="button" 
                        onclick="switchCompositionTab('new')"
                        id="tab-new"
                        class="flex-1 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-admin-500 hover:text-admin-700 transition">
                    Créer un nouveau
                </button>
                <button type="button" 
                        onclick="closeCompositionModal()"
                        class="px-4 py-3 text-admin-400 hover:text-admin-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- CONTENU : Composant existant --}}
        {{-- ============================================================ --}}
        <div id="content-existing" class="flex-1 overflow-y-auto">
            <form action="{{ route('admin.ouvrages.composition.store', $ouvrage) }}" method="POST">
                @csrf
                <div class="p-4 space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">
                            Composant <span class="text-red-500">*</span>
                        </label>
                        <select name="composant_id" required 
                                class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="">Sélectionner un composant existant</option>
                            @foreach($composantsDisponibles as $c)
                                <option value="{{ $c->id }}">
                                    {{ $c->reference }} — {{ $c->designation }}
                                    @if($c->typeComposant) ({{ $c->typeComposant->nom }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-admin-600 mb-1">Quantité</label>
                            <input type="number" name="quantite" step="0.01" min="0.01" value="1" required
                                   class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-admin-600 mb-1">Unité</label>
                            <select name="unite" class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="u">u</option>
                                <option value="m">m</option>
                                <option value="kg">kg</option>
                                <option value="ml">ml</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-admin-600 mb-1">Commentaire</label>
                        <textarea name="commentaire" rows="2"
                                  placeholder="Remarque technique..."
                                  class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                    </div>
                </div>

                <div class="p-4 border-t border-admin-200 flex justify-end gap-2 bg-admin-50">
                    <button type="button" 
                            onclick="closeCompositionModal()"
                            class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                        Ajouter à la composition
                    </button>
                </div>
            </form>
        </div>

        {{-- ============================================================ --}}
        {{-- CONTENU : Nouveau composant --}}
        {{-- ============================================================ --}}
        <div id="content-new" class="hidden flex-1 overflow-y-auto">
            <form action="{{ route('admin.ouvrages.composition.store-new', $ouvrage) }}" method="POST">
                @csrf
                <div class="p-4 space-y-3">
                    
                    {{-- Identification --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-admin-600 mb-1">
                                Référence <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="reference" required
                                   placeholder="PRO-004-45"
                                   class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-admin-600 mb-1">
                                Désignation <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="designation" required
                                   placeholder="Profilé spécial"
                                   class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    {{-- Type + Matière --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-admin-600 mb-1">Type de composant</label>
                            <select name="type_composant_id" id="new-type-composant"
                                    class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">— Sélectionner —</option>
                                @foreach($typesComposant as $type)
                                    <option value="{{ $type->id }}" data-slug="{{ $type->slug }}">
                                        {{ $type->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-admin-600 mb-1">Matière</label>
                            <input type="text" name="matiere" placeholder="Alu 6060-T6"
                                   class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- SECTION CONDITIONNELLE : Champs profilés --}}
                    {{-- ============================================================ --}}
                    <div id="new-section-profile" class="hidden space-y-3 pt-3 border-t border-admin-100">
                        <h4 class="text-xs font-semibold text-admin-500 uppercase tracking-wider">
                            Caractéristiques techniques (profilé)
                        </h4>

                        {{-- Dimensions --}}
                        <div class="grid grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs text-admin-500 mb-1">Long. barre (mm)</label>
                                <input type="number" name="longueur_barre_mm" min="0" placeholder="6000"
                                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs text-admin-500 mb-1">Larg. sec. (mm)</label>
                                <input type="number" name="section_largeur_mm" step="0.01" min="0" placeholder="45.00"
                                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs text-admin-500 mb-1">Haut. sec. (mm)</label>
                                <input type="number" name="section_hauteur_mm" step="0.01" min="0" placeholder="35.00"
                                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs text-admin-500 mb-1">Épais. paroi (mm)</label>
                                <input type="number" name="epaisseur_paroi_mm" step="0.01" min="0" placeholder="1.50"
                                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                        </div>

                        {{-- Poids (KG/M + WT/FT) --}}
                        <div class="p-3 bg-admin-50 rounded border border-admin-100">
                            <h5 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                                Poids linéaire
                            </h5>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-admin-500 mb-1">
                                        KG/M <span class="text-admin-400">(métrique)</span>
                                    </label>
                                    <input type="number" name="poids_lineaire_kg_m" id="new-kg-m" step="0.001" min="0"
                                           placeholder="2.450"
                                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-admin-500 mb-1">
                                        WT/FT <span class="text-admin-400">(impérial)</span>
                                    </label>
                                    <input type="number" name="poids_lineaire_lbs_ft" id="new-wt-ft" step="0.001" min="0"
                                           placeholder="1.647"
                                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>

                        {{-- Inertie + Périmètre --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-admin-50 rounded border border-admin-100">
                                <h5 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                                    Inertie
                                </h5>
                                <div>
                                    <label class="block text-xs text-admin-500 mb-1">
                                        IN <span class="text-admin-400">(cm⁴)</span>
                                    </label>
                                    <input type="number" name="moment_inertie_cm4" step="0.01" min="0"
                                           placeholder="85.30"
                                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                            </div>
                            <div class="p-3 bg-admin-50 rounded border border-admin-100">
                                <h5 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                                    Périmètre
                                </h5>
                                <div>
                                    <label class="block text-xs text-admin-500 mb-1">
                                        PERIM. <span class="text-admin-400">(mm)</span>
                                    </label>
                                    <input type="number" name="perimetre_mm" step="0.01" min="0"
                                           placeholder="205.39"
                                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ============================================================ --}}
                    {{-- SECTION COMPOSITION --}}
                    {{-- ============================================================ --}}
                    <div class="pt-3 border-t border-admin-100">
                        <h4 class="text-xs font-semibold text-admin-500 uppercase tracking-wider mb-2">
                            Dans la composition
                        </h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-admin-500 mb-1">Quantité</label>
                                <input type="number" name="quantite" step="0.01" min="0.01" value="1" required
                                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs text-admin-500 mb-1">Unité</label>
                                <select name="unite" class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                                    <option value="u">u</option>
                                    <option value="m">m</option>
                                    <option value="kg">kg</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="p-4 border-t border-admin-200 flex justify-end gap-2 bg-admin-50">
                    <button type="button" 
                            onclick="closeCompositionModal()"
                            class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded transition">
                        Créer et ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // ============================================================
    // GESTION DE LA MODAL
    // ============================================================
    function openCompositionModal() {
        document.getElementById('modal-composition').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        switchCompositionTab('existing');
    }

    function closeCompositionModal() {
        document.getElementById('modal-composition').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCompositionModal();
        }
    });

    // ============================================================
    // GESTION DES ONGLETS
    // ============================================================
    function switchCompositionTab(tab) {
        // Contenu
        document.getElementById('content-existing').classList.toggle('hidden', tab !== 'existing');
        document.getElementById('content-new').classList.toggle('hidden', tab !== 'new');
        
        // Boutons
        const tabExisting = document.getElementById('tab-existing');
        const tabNew = document.getElementById('tab-new');
        
        if (tab === 'existing') {
            tabExisting.className = 'flex-1 px-4 py-3 text-sm font-medium border-b-2 border-amber-500 text-amber-700 transition';
            tabNew.className = 'flex-1 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-admin-500 hover:text-admin-700 transition';
        } else {
            tabExisting.className = 'flex-1 px-4 py-3 text-sm font-medium border-b-2 border-transparent text-admin-500 hover:text-admin-700 transition';
            tabNew.className = 'flex-1 px-4 py-3 text-sm font-medium border-b-2 border-amber-500 text-amber-700 transition';
        }
    }

    // ============================================================
    // AFFICHAGE CONDITIONNEL DES CHAMPS PROFILÉ
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('new-type-composant');
        const sectionProfile = document.getElementById('new-section-profile');

        if (typeSelect && sectionProfile) {
            function updateProfileFields() {
                const selectedOption = typeSelect.options[typeSelect.selectedIndex];
                const slug = selectedOption?.dataset?.slug;

                if (slug === 'profile' || slug === 'profil') {
                    sectionProfile.classList.remove('hidden');
                } else {
                    sectionProfile.classList.add('hidden');
                }
            }

            typeSelect.addEventListener('change', updateProfileFields);
            updateProfileFields(); // Au chargement
        }

        // ============================================================
        // CONVERSIONS AUTOMATIQUES
        // ============================================================
        const kgInput = document.getElementById('new-kg-m');
        const wtInput = document.getElementById('new-wt-ft');

        if (kgInput && wtInput) {
            // KG/M → WT/FT
            kgInput.addEventListener('input', function() {
                if (this.value && !wtInput.dataset.touched) {
                    wtInput.value = (parseFloat(this.value) * 0.672).toFixed(3);
                }
                kgInput.dataset.touched = 'true';
            });

            // WT/FT → KG/M
            wtInput.addEventListener('input', function() {
                if (this.value && !kgInput.dataset.touched) {
                    kgInput.value = (parseFloat(this.value) / 0.672).toFixed(3);
                }
                wtInput.dataset.touched = 'true';
            });
        }

        // ============================================================
        // DRAG & DROP
        // ============================================================
        const list = document.getElementById('composition-list');
        if (list && list.children.length > 1) {
            new Sortable(list, {
                animation: 150,
                handle: '.cursor-move',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
                    const items = Array.from(list.children)
                        .filter(el => el.dataset.id)
                        .map(el => el.dataset.id);
                    
                    fetch('{{ route('admin.ouvrages.composition.reorder', $ouvrage) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ ordre: items })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('✅ Ordre sauvegardé');
                        }
                    })
                    .catch(err => console.error('❌ Erreur réordonnancement:', err));
                }
            });
        }
    });
</script>
@endpush