{{-- resources/views/admin/ouvrages/partials/caracteristiques.blade.php --}}
<div class="bg-white rounded border border-admin-200">
    <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-admin-700">
            Caractéristiques techniques
            <span class="text-xs text-admin-400 font-normal ml-1">({{ $ouvrage->caracteristiques->count() }})</span>
        </h2>
        <button type="button" 
                onclick="document.getElementById('modal-add-caracteristique').classList.remove('hidden')"
                class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-xs rounded transition">
            + Ajouter
        </button>
    </div>

    {{-- Liste des caractéristiques --}}
    <div id="caracteristiques-list" class="divide-y divide-admin-100">
        @forelse($ouvrage->caracteristiques as $carac)
            <div class="p-3 hover:bg-admin-50 transition group" data-id="{{ $carac->id }}">
                <div class="flex items-center gap-3">
                    
                    {{-- Drag handle --}}
                    <div class="cursor-move text-admin-300 hover:text-admin-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                        </svg>
                    </div>

                    {{-- Contenu --}}
                    <div class="flex-1 min-w-0">
                        <form action="{{ route('admin.ouvrages.caracteristiques.update', [$ouvrage, $carac]) }}" 
                              method="POST" 
                              class="flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            
                            {{-- Clé --}}
                            <input type="text" 
                                   name="cle" 
                                   value="{{ $carac->cle }}" 
                                   required
                                   placeholder="Clé (ex: Épaisseur)"
                                   class="w-40 px-2 py-1 text-xs border border-transparent hover:border-admin-200 focus:border-amber-500 rounded focus:outline-none focus:ring-1 focus:ring-amber-500 bg-transparent">
                            
                            {{-- Valeur --}}
                            <input type="text" 
                                   name="valeur" 
                                   value="{{ $carac->valeur }}" 
                                   required
                                   placeholder="Valeur"
                                   class="flex-1 px-2 py-1 text-xs border border-transparent hover:border-admin-200 focus:border-amber-500 rounded focus:outline-none focus:ring-1 focus:ring-amber-500 bg-transparent">
                            
                            {{-- Unité --}}
                            <input type="text" 
                                   name="unite" 
                                   value="{{ $carac->unite }}" 
                                   placeholder="Unité"
                                   class="w-20 px-2 py-1 text-xs border border-transparent hover:border-admin-200 focus:border-amber-500 rounded focus:outline-none focus:ring-1 focus:ring-amber-500 bg-transparent">
                            
                            {{-- Ordre --}}
                            <input type="hidden" name="ordre_affichage" value="{{ $carac->ordre_affichage }}">
                            
                            {{-- Bouton save (visible au hover) --}}
                            <button type="submit" 
                                    class="opacity-0 group-hover:opacity-100 text-xs text-admin-500 hover:text-admin-900 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                        <form action="{{ route('admin.ouvrages.caracteristiques.destroy', [$ouvrage, $carac]) }}" 
                              method="POST" 
                              class="inline"
                              onsubmit="return confirm('Supprimer cette caractéristique ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="p-1 text-red-400 hover:text-red-600 transition"
                                    title="Supprimer">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Aucune caractéristique.
                <button type="button" 
                        onclick="document.getElementById('modal-add-caracteristique').classList.remove('hidden')"
                        class="text-admin-600 hover:text-admin-800 underline ml-1">
                    Ajouter la première
                </button>
            </div>
        @endforelse
    </div>

    {{-- Aide --}}
    @if($ouvrage->caracteristiques->count() > 0)
        <div class="px-4 py-2 bg-admin-50 border-t border-admin-100 text-xs text-admin-400">
            💡 Modifiez les valeurs directement dans les champs. Les modifications sont enregistrées en cliquant sur ✓.
        </div>
    @endif
</div>

{{-- Modal Ajout Caractéristique --}}
<div id="modal-add-caracteristique" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded shadow-xl max-w-md w-full">
        <form action="{{ route('admin.ouvrages.caracteristiques.store', $ouvrage) }}" method="POST">
            @csrf
            
            <div class="p-4 border-b border-admin-200 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-admin-900">Ajouter une caractéristique</h3>
                <button type="button" 
                        onclick="document.getElementById('modal-add-caracteristique').classList.add('hidden')"
                        class="text-admin-400 hover:text-admin-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-4 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">
                        Clé <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="cle" 
                           required
                           placeholder="Ex: Épaisseur, Matière, Norme..."
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">
                        Valeur <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="valeur" 
                           required
                           placeholder="Ex: 1.5, Alu 6060-T6, EN 755..."
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Unité (optionnel)</label>
                    <input type="text" 
                           name="unite" 
                           placeholder="Ex: mm, kg/m, °C..."
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label class="block text-xs font-medium text-admin-600 mb-1">Ordre d'affichage</label>
                    <input type="number" 
                           name="ordre_affichage" 
                           value="{{ $ouvrage->caracteristiques->count() + 1 }}"
                           min="0"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <div class="p-4 border-t border-admin-200 flex justify-end gap-2">
                <button type="button" 
                        onclick="document.getElementById('modal-add-caracteristique').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Drag & drop pour réordonner (optionnel) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const list = document.getElementById('caracteristiques-list');
        if (!list) return;

        new Sortable(list, {
            animation: 150,
            handle: '.cursor-move',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                const items = Array.from(list.children).map(el => el.dataset.id);
                
                fetch('{{ route('admin.ouvrages.caracteristiques.reorder', $ouvrage) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ordre: items })
                }).catch(err => console.error('Erreur réordonnancement:', err));
            }
        });
    });
</script>
@endpush