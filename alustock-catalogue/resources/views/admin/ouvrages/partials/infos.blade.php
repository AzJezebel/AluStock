{{-- resources/views/admin/ouvrages/partials/infos.blade.php --}}
<div class="bg-white rounded border border-admin-200">
    <div class="px-4 py-3 border-b border-admin-200">
        <h2 class="text-sm font-semibold text-admin-700">Informations générales</h2>
    </div>

    <form action="{{ route('admin.ouvrages.update', $ouvrage) }}" method="POST" class="p-4 space-y-4">
        @csrf
        @method('PUT')

        {{-- Identification --}}
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

        {{-- Classification --}}
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

        {{-- Descriptions --}}
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

        {{-- Dimensions --}}
        <div>
            <h3 class="text-xs font-medium text-admin-500 uppercase tracking-wider mb-2">Dimensions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div>
                    <label for="largeur_min_mm" class="block text-xs text-admin-500 mb-1">Largeur min (mm)</label>
                    <input type="number" name="largeur_min_mm" id="largeur_min_mm" min="0"
                           value="{{ old('largeur_min_mm', $ouvrage->largeur_min_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="largeur_max_mm" class="block text-xs text-admin-500 mb-1">Largeur max (mm)</label>
                    <input type="number" name="largeur_max_mm" id="largeur_max_mm" min="0"
                           value="{{ old('largeur_max_mm', $ouvrage->largeur_max_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="hauteur_min_mm" class="block text-xs text-admin-500 mb-1">Hauteur min (mm)</label>
                    <input type="number" name="hauteur_min_mm" id="hauteur_min_mm" min="0"
                           value="{{ old('hauteur_min_mm', $ouvrage->hauteur_min_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="hauteur_max_mm" class="block text-xs text-admin-500 mb-1">Hauteur max (mm)</label>
                    <input type="number" name="hauteur_max_mm" id="hauteur_max_mm" min="0"
                           value="{{ old('hauteur_max_mm', $ouvrage->hauteur_max_mm) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        {{-- Performances --}}
        <div>
            <h3 class="text-xs font-medium text-admin-500 uppercase tracking-wider mb-2">Performances</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label for="performance_thermique" class="block text-xs text-admin-500 mb-1">Thermique</label>
                    <input type="text" name="performance_thermique" id="performance_thermique"
                           value="{{ old('performance_thermique', $ouvrage->performance_thermique) }}"
                           placeholder="Uw = 1.8 W/m²K"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                <div>
                    <label for="performance_acoustique" class="block text-xs text-admin-500 mb-1">Acoustique</label>
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

        {{-- Submit --}}
        <div class="flex justify-end pt-3 border-t border-admin-100">
            <button type="submit" 
                    class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>