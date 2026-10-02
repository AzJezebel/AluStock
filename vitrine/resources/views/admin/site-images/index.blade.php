{{-- resources/views/admin/site-images/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Images du site - Administration')

@section('content')
@php
    $slots = [
        'hero' => [
            'title'    => "Hero — diaporama de l'accueil",
            'help'     => "Plusieurs images, glissez-les pour changer l'ordre. Format paysage conseillé (1920 × 1080 minimum).",
            'multiple' => true,
        ],
        'about' => [
            'title'    => 'Photo « À propos »',
            'help'     => "Une seule image : en envoyer une nouvelle remplace l'ancienne. Format portrait conseillé (4:5).",
            'multiple' => false,
        ],
    ];
@endphp
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Images du site</h1>
            <p class="text-xs text-admin-500 mt-0.5">Ce qui s'affiche sur la page d'accueil de la vitrine</p>
        </div>
        <a href="{{ route('vitrine.index') }}" target="_blank"
           class="px-3 py-1.5 bg-admin-100 hover:bg-admin-200 text-admin-700 text-sm rounded transition">
            Voir le site ↗
        </a>
    </div>

    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @foreach($slots as $slot => $cfg)
        @php $images = \App\Support\SiteImages::get($slot); @endphp

        <div class="bg-white rounded border border-admin-200 mb-6"
             data-slot="{{ $slot }}"
             data-multiple="{{ $cfg['multiple'] ? 1 : 0 }}"
             data-reorder-url="{{ route('admin.site-images.reorder', $slot) }}">

            {{-- En-tête --}}
            <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-admin-700">
                    {{ $cfg['title'] }}
                    <span class="text-xs text-admin-400 font-normal ml-1">({{ count($images) }})</span>
                </h2>
                <span data-status class="text-xs text-emerald-600"></span>
            </div>

            {{-- Zone d'upload --}}
            <div class="p-4 border-b border-admin-100">
                <div data-dropzone
                     class="border-2 border-dashed border-admin-200 rounded-lg p-6 text-center transition cursor-pointer hover:border-amber-400 hover:bg-amber-50/30">
                    <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-admin-600 font-medium">
                        {{ $cfg['multiple'] ? 'Glissez vos images ici' : 'Glissez votre image ici' }}
                    </p>
                    <p class="text-xs text-admin-400 mt-1">
                        ou <span class="text-amber-600 underline font-medium">parcourez vos fichiers</span>
                    </p>
                    <p class="text-xs text-admin-300 mt-2">PNG, JPG, WebP — Max 8 Mo par fichier{{ $cfg['multiple'] ? ' — 10 fichiers max' : '' }}</p>
                </div>
                <p class="text-xs text-admin-400 mt-2">{{ $cfg['help'] }}</p>

                <input type="file" data-file-input accept=".png,.jpg,.jpeg,.webp" {{ $cfg['multiple'] ? 'multiple' : '' }} class="hidden">
            </div>

            {{-- Aperçu des fichiers à envoyer --}}
            <div data-preview-container class="hidden p-4 border-b border-admin-100 bg-amber-50/50">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold text-admin-600 uppercase tracking-wider">
                        À envoyer (<span data-preview-count>0</span>)
                    </h3>
                    <button type="button" data-clear-all class="text-xs text-red-500 hover:text-red-700">Tout effacer</button>
                </div>
                <div data-preview-grid class="grid grid-cols-4 md:grid-cols-6 gap-2 mb-3"></div>
                <div class="flex justify-end">
                    <button type="button" data-upload-btn
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded transition">
                        {{ $cfg['multiple'] ? 'Envoyer les images' : "Remplacer l'image" }}
                    </button>
                </div>
            </div>

            {{-- Images actuelles --}}
            @if(count($images) > 0)
                <div class="p-4">
                    <h3 class="text-xs font-semibold text-admin-600 uppercase tracking-wider mb-3">
                        {{ $cfg['multiple'] ? 'Images actuelles — ordre du diaporama' : 'Image actuelle' }}
                    </h3>

                    <div data-list class="grid {{ $cfg['multiple'] ? 'grid-cols-2 md:grid-cols-4' : 'grid-cols-1 max-w-xs' }} gap-3">
                        @foreach($images as $img)
                            <div data-path="{{ $img['path'] }}"
                                 class="relative group bg-admin-50 rounded border border-admin-200 overflow-hidden {{ $cfg['multiple'] ? 'cursor-move' : '' }}">

                                @if($cfg['multiple'])
                                    <div data-order class="absolute top-1 left-1 z-10 bg-admin-900/80 text-white text-[10px] font-semibold w-5 h-5 rounded flex items-center justify-center">
                                        {{ $loop->iteration }}
                                    </div>
                                @endif

                                <div class="{{ $cfg['multiple'] ? 'aspect-video' : 'aspect-[4/5]' }} bg-white overflow-hidden">
                                    <img src="{{ $img['url'] }}" alt="" draggable="false" class="w-full h-full object-cover">
                                </div>

                                <div class="p-2 bg-white border-t border-admin-100">
                                    <p class="text-[10px] text-admin-500 truncate" title="{{ basename($img['path']) }}">{{ basename($img['path']) }}</p>
                                </div>

                                <div data-no-drag class="absolute top-1 right-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition">
                                    <form action="{{ route('admin.site-images.destroy', $slot) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cette image ?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $img['path'] }}">
                                        <button type="submit" title="Supprimer"
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
                    Aucune image pour l'instant — le site affiche un visuel de remplacement.
                </div>
            @endif

            {{-- Formulaire d'envoi caché (soumis via JS) --}}
            <form data-upload-form action="{{ route('admin.site-images.store', $slot) }}" method="POST"
                  enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" name="fichiers[]" data-upload-form-input multiple>
            </form>
        </div>
    @endforeach
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ALLOWED = ['image/png', 'image/jpeg', 'image/webp'];
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    document.querySelectorAll('[data-slot]').forEach(initSlot);

    function initSlot(box) {
        const multiple = box.dataset.multiple === '1';
        const max = multiple ? 10 : 1;

        const dropzone = box.querySelector('[data-dropzone]');
        const fileInput = box.querySelector('[data-file-input]');
        const previewContainer = box.querySelector('[data-preview-container]');
        const previewGrid = box.querySelector('[data-preview-grid]');
        const previewCount = box.querySelector('[data-preview-count]');
        const uploadBtn = box.querySelector('[data-upload-btn]');
        const clearAllBtn = box.querySelector('[data-clear-all]');
        const uploadForm = box.querySelector('[data-upload-form]');
        const uploadFormInput = box.querySelector('[data-upload-form-input]');
        const status = box.querySelector('[data-status]');

        let pending = [];

        // ---- sélection / glisser-déposer
        dropzone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', (e) => { addFiles(Array.from(e.target.files)); fileInput.value = ''; });

        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('border-amber-400', 'bg-amber-50'); });
        dropzone.addEventListener('dragleave', () => dropzone.classList.remove('border-amber-400', 'bg-amber-50'));
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-amber-400', 'bg-amber-50');
            addFiles(Array.from(e.dataTransfer.files));
        });

        function addFiles(files) {
            files = files.filter((f) => ALLOWED.includes(f.type));
            if (!files.length) return;

            if (!multiple) {
                pending = [files[files.length - 1]];          // une seule image : la dernière choisie
            } else {
                if (pending.length + files.length > max) {
                    alert('Maximum ' + max + ' fichiers à la fois.');
                    files = files.slice(0, max - pending.length);
                }
                pending = pending.concat(files);
            }
            renderPreview();
        }

        function renderPreview() {
            previewGrid.querySelectorAll('img').forEach((i) => URL.revokeObjectURL(i.src));
            previewGrid.innerHTML = '';

            if (!pending.length) { previewContainer.classList.add('hidden'); return; }
            previewContainer.classList.remove('hidden');
            previewCount.textContent = pending.length;

            pending.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'relative aspect-square bg-admin-50 rounded border border-admin-200 overflow-hidden group';
                div.innerHTML =
                    '<img class="w-full h-full object-cover" alt="">' +
                    '<button type="button" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-100 md:opacity-0 md:group-hover:opacity-100 transition" aria-label="Retirer">' +
                        '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                    '</button>' +
                    '<div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] p-1 truncate"></div>';
                div.querySelector('img').src = URL.createObjectURL(file);
                div.querySelector('div').textContent = file.name;
                div.querySelector('button').addEventListener('click', () => { pending.splice(index, 1); renderPreview(); });
                previewGrid.appendChild(div);
            });
        }

        clearAllBtn.addEventListener('click', () => { pending = []; renderPreview(); });

        uploadBtn.addEventListener('click', () => {
            if (!pending.length) return;
            const dt = new DataTransfer();
            pending.forEach((f) => dt.items.add(f));
            uploadFormInput.files = dt.files;
            uploadBtn.disabled = true;
            uploadBtn.textContent = 'Envoi en cours…';
            uploadForm.submit();
        });

        // ---- ordre (glisser-déposer) — emplacements multiples seulement
        const list = box.querySelector('[data-list]');
        if (multiple && list && window.Sortable) {
            new Sortable(list, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                filter: '[data-no-drag]',
                preventOnFilter: false,
                onEnd: saveOrder,
            });
        }

        function saveOrder() {
            const order = Array.from(list.querySelectorAll('[data-path]')).map((el) => el.dataset.path);
            list.querySelectorAll('[data-order]').forEach((b, i) => { b.textContent = i + 1; });

            status.className = 'text-xs text-admin-400';
            status.textContent = 'Enregistrement…';

            fetch(box.dataset.reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({ order }),
            })
                .then((r) => { if (!r.ok) throw new Error(r.status); return r.json(); })
                .then(() => {
                    status.className = 'text-xs text-emerald-600';
                    status.textContent = 'Ordre enregistré ✓';
                    setTimeout(() => { status.textContent = ''; }, 2500);
                })
                .catch(() => {
                    status.className = 'text-xs text-red-600';
                    status.textContent = "Échec de l'enregistrement — rechargez la page";
                });
        }
    }
});
</script>
@endpush