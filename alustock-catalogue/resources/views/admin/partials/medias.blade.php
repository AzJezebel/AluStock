{{-- resources/views/admin/partials/medias.blade.php --}}
{{-- 
    Variables attendues :
    - $entity : l'entité (Ouvrage ou Composant)
    - $entityType : 'ouvrage' ou 'composant'
    - $uploadRoute : (optionnel) route d'upload. Si non fourni, déduit automatiquement.
--}}

@php
    $routeUpload = $uploadRoute ?? (
        $entityType === 'ouvrage' 
            ? route('admin.medias.ouvrages.upload', $entity) 
            : route('admin.medias.composants.upload', $entity)
    );
    $uniqueId = $entityType . '-' . $entity->id;
@endphp

<div class="bg-white rounded border border-admin-200" data-medias-uploader="{{ $uniqueId }}">
    {{-- En-tête --}}
    <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-admin-700">
            Schémas et images
            <span class="text-xs text-admin-400 font-normal ml-1">({{ $entity->medias->count() }})</span>
        </h2>
    </div>

    {{-- Zone d'upload --}}
    <div class="p-4 border-b border-admin-100">
        
        {{-- Type de média --}}
        <div class="mb-3">
            <label class="block text-xs font-medium text-admin-600 mb-1">Type de média</label>
            <select data-medias-type
                    class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                <option value="schema">Schéma technique</option>
                <option value="photo">Photo</option>
                <option value="rendu_3d">Rendu 3D</option>
            </select>
        </div>

        {{-- Zone de drop --}}
        <div data-dropzone
             class="border-2 border-dashed border-admin-200 rounded-lg p-6 text-center transition cursor-pointer hover:border-amber-400 hover:bg-amber-50/30">
            
            <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-admin-600 font-medium">
                Glissez vos images ici
            </p>
            <p class="text-xs text-admin-400 mt-1">
                ou <span class="text-amber-600 underline font-medium">parcourez vos fichiers</span>
            </p>
            <p class="text-xs text-admin-300 mt-2">PNG, JPG — Max 5 Mo par fichier — 10 fichiers max</p>
        </div>

        {{-- Input caché --}}
        <input type="file" 
               data-file-input
               accept=".png,.jpg,.jpeg"
               multiple
               class="hidden">
    </div>

    {{-- Aperçu des fichiers à uploader --}}
    <div data-preview-container class="hidden p-4 border-b border-admin-100 bg-amber-50/50">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-semibold text-admin-600 uppercase tracking-wider">
                À uploader (<span data-preview-count>0</span>)
            </h3>
            <button type="button" 
                    data-clear-all
                    class="text-xs text-red-500 hover:text-red-700">
                Tout effacer
            </button>
        </div>
        <div data-preview-grid class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-3"></div>
        <div class="flex justify-end">
            <button type="button" 
                    data-upload-btn
                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded transition">
                Uploader les images
            </button>
        </div>
    </div>

    {{-- Liste des médias existants --}}
    @if($entity->medias->count() > 0)
        <div data-existing-medias class="p-4">
            <h3 class="text-xs font-semibold text-admin-600 uppercase tracking-wider mb-3">
                Images existantes
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($entity->medias as $media)
                    <div class="relative group bg-admin-50 rounded border border-admin-200 overflow-hidden">
                        {{-- Badge principal --}}
                        @if($media->est_principal)
                            <div class="absolute top-1 left-1 z-10 bg-amber-500 text-white text-[9px] font-semibold px-1.5 py-0.5 rounded">
                                PRINCIPAL
                            </div>
                        @endif

                        {{-- Image --}}
                        <div class="aspect-square bg-white flex items-center justify-center overflow-hidden">
                            <img src="{{ asset('storage/' . $media->chemin_fichier) }}" 
                                 alt="{{ $media->titre }}"
                                 class="w-full h-full object-contain">
                        </div>

                        {{-- Titre --}}
                        <div class="p-2 bg-white border-t border-admin-100">
                            <p class="text-[10px] text-admin-500 truncate" title="{{ $media->titre }}">
                                {{ $media->titre }}
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="absolute top-1 right-1 flex gap-1 opacity-0 group-hover:opacity-100 transition">
                            @if(!$media->est_principal)
                                <form action="{{ route('admin.medias.principal', $media) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="entity_type" value="{{ $entityType }}">
                                    <input type="hidden" name="entity_id" value="{{ $entity->id }}">
                                    <button type="submit" 
                                            title="Définir comme principal"
                                            class="p-1 bg-white/90 rounded hover:bg-amber-100 transition">
                                        <svg class="w-3 h-3 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.098 9.1c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.medias.destroy', $media) }}" method="POST"
                                  onsubmit="return confirm('Supprimer cette image ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        title="Supprimer"
                                        class="p-1 bg-white/90 rounded hover:bg-red-100 transition">
                                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="p-8 text-center text-sm text-admin-400">
            Aucune image pour l'instant.
        </div>
    @endif

    {{-- Formulaire d'upload caché (soumis via JS) --}}
    <form data-upload-form 
          action="{{ $routeUpload }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="hidden">
        @csrf
        <input type="file" name="fichiers[]" data-upload-form-input multiple>
        <input type="hidden" name="type_media" data-upload-form-type value="schema">
    </form>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser tous les uploaders
    document.querySelectorAll('[data-medias-uploader]').forEach(initMediasUploader);

    function initMediasUploader(container) {
        const dropzone = container.querySelector('[data-dropzone]');
        const fileInput = container.querySelector('[data-file-input]');
        const typeSelect = container.querySelector('[data-medias-type]');
        const previewContainer = container.querySelector('[data-preview-container]');
        const previewGrid = container.querySelector('[data-preview-grid]');
        const previewCount = container.querySelector('[data-preview-count]');
        const uploadBtn = container.querySelector('[data-upload-btn]');
        const clearAllBtn = container.querySelector('[data-clear-all]');
        const uploadForm = container.querySelector('[data-upload-form]');
        const uploadFormInput = container.querySelector('[data-upload-form-input]');
        const uploadFormType = container.querySelector('[data-upload-form-type]');

        let pendingFiles = [];

        // Clic sur la dropzone → ouvrir le file picker
        dropzone.addEventListener('click', () => fileInput.click());

        // Sélection via le file picker
        fileInput.addEventListener('change', (e) => {
            addFiles(Array.from(e.target.files));
            fileInput.value = ''; // Reset pour permettre de re-sélectionner le même fichier
        });

        // Drag & drop
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

        // Ajouter des fichiers
        function addFiles(files) {
            // Limiter à 10 fichiers au total
            const total = pendingFiles.length + files.length;
            if (total > 10) {
                alert('Maximum 10 fichiers à la fois.');
                files = files.slice(0, 10 - pendingFiles.length);
            }

            pendingFiles = [...pendingFiles, ...files];
            renderPreview();
        }

        // Afficher l'aperçu
        function renderPreview() {
            if (pendingFiles.length === 0) {
                previewContainer.classList.add('hidden');
                return;
            }

            previewContainer.classList.remove('hidden');
            previewCount.textContent = pendingFiles.length;
            previewGrid.innerHTML = '';

            pendingFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square bg-admin-50 rounded border border-admin-200 overflow-hidden group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-contain">
                        <button type="button" 
                                data-remove-index="${index}"
                                class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] p-1 truncate">
                            ${file.name}
                        </div>
                    `;
                    previewGrid.appendChild(div);

                    // Bouton supprimer
                    div.querySelector('[data-remove-index]').addEventListener('click', () => {
                        pendingFiles.splice(index, 1);
                        renderPreview();
                    });
                };
                reader.readAsDataURL(file);
            });
        }

        // Tout effacer
        clearAllBtn.addEventListener('click', () => {
            if (confirm('Effacer toutes les images en attente ?')) {
                pendingFiles = [];
                renderPreview();
            }
        });

        // Uploader
        uploadBtn.addEventListener('click', () => {
            if (pendingFiles.length === 0) {
                alert('Aucune image à uploader.');
                return;
            }

            // Créer un DataTransfer pour assigner les fichiers au formulaire
            const dt = new DataTransfer();
            pendingFiles.forEach(file => dt.items.add(file));
            
            uploadFormInput.files = dt.files;
            uploadFormType.value = typeSelect.value;

            // Soumettre
            uploadForm.submit();
        });
    }
});
</script>
@endpush
@endonce