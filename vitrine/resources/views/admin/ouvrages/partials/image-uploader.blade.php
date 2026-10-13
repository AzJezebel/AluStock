{{-- resources/views/admin/ouvrages/partials/image-uploader.blade.php
    Zone d'upload d'images (même interface que « Images du site »), à placer DANS le formulaire de l'ouvrage.
    Les fichiers choisis sont injectés dans un vrai <input type="file"> du formulaire : le contrôleur
    reçoit donc exactement les mêmes champs qu'avant (main_image / gallery_images[]).

    Variables :
      $name       : 'main_image' ou 'gallery_images'
      $multiple   : (bool) plusieurs fichiers ? (défaut false)
      $label      : titre de la zone
      $help       : (optionnel) texte d'aide
      $existing   : (optionnel) images déjà enregistrées (objets avec ->id et ->file_path)
      $deletable  : (optionnel) croix de suppression sur les images existantes. Elle déclenche un
                    formulaire EXTERNE d'id « del-img-{id} » (voir edit.blade.php) : on ne peut pas
                    imbriquer un <form> dans un <form>.
      $maxMb      : (optionnel) poids max par fichier, défaut 2
      $maxFiles   : (optionnel) nombre max de fichiers (galerie), défaut 10
--}}
@php
    $multiple  = $multiple ?? false;
    $existing  = collect($existing ?? []);
    $deletable = $deletable ?? false;
    $maxMb     = $maxMb ?? 2;
    $maxFiles  = $multiple ? ($maxFiles ?? 10) : 1;
    $inputName = $multiple ? $name . '[]' : $name;
    $serverErrors = collect($errors->get($name))
        ->merge($multiple ? $errors->get($name . '.*') : [])
        ->flatten()->unique()->values();
@endphp

<div class="bg-white rounded border border-admin-200"
     data-img-uploader
     data-multiple="{{ $multiple ? 1 : 0 }}"
     data-max="{{ $maxFiles }}"
     data-max-mb="{{ $maxMb }}">

    {{-- En-tête --}}
    <div class="px-4 py-3 border-b border-admin-200">
        <h3 class="text-sm font-semibold text-admin-700">
            {{ $label }}
            @if($multiple && $existing->count())
                <span class="text-xs text-admin-400 font-normal ml-1">({{ $existing->count() }})</span>
            @endif
        </h3>
        @isset($help)
            <p class="text-xs text-admin-400 mt-0.5">{{ $help }}</p>
        @endisset
    </div>

    {{-- Images déjà enregistrées --}}
    @if($existing->isNotEmpty())
        <div class="p-4 border-b border-admin-100">
            <h4 class="text-xs font-semibold text-admin-600 uppercase tracking-wider mb-3">
                {{ $multiple ? 'Images actuelles' : 'Image actuelle' }}
            </h4>
            <div class="grid {{ $multiple ? 'grid-cols-3 md:grid-cols-4' : 'grid-cols-1 max-w-[12rem]' }} gap-3">
                @foreach($existing as $img)
                    <div class="relative group bg-admin-50 rounded border border-admin-200 overflow-hidden">
                        <div class="aspect-square bg-white">
                            <img src="{{ asset('storage/' . $img->file_path) }}" alt="" class="w-full h-full object-cover">
                        </div>
                        @if($deletable)
                            <button type="submit" form="del-img-{{ $img->id }}" title="Supprimer"
                                    onclick="return confirm('Supprimer cette image ? Les modifications non enregistrées du formulaire seront perdues.')"
                                    class="absolute top-1 right-1 p-1 bg-white/90 rounded hover:bg-red-100 transition opacity-100 md:opacity-0 md:group-hover:opacity-100">
                                <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Zone de dépôt --}}
    <div class="p-4">
        <div data-dropzone
             class="border-2 border-dashed border-admin-200 rounded-lg p-6 text-center transition cursor-pointer hover:border-amber-400 hover:bg-amber-50/30">
            <svg class="w-10 h-10 mx-auto mb-3 text-admin-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-admin-600 font-medium">
                @if($multiple)
                    Glissez vos images ici
                @else
                    {{ $existing->isNotEmpty() ? "Glissez une image pour remplacer l'actuelle" : 'Glissez votre image ici' }}
                @endif
            </p>
            <p class="text-xs text-admin-400 mt-1">
                ou <span class="text-amber-600 underline font-medium">parcourez vos fichiers</span>
            </p>
            <p class="text-xs text-admin-300 mt-2">
                JPG, PNG, GIF, WebP — Max {{ $maxMb }} Mo par fichier{{ $multiple ? ' — ' . $maxFiles . ' fichiers max' : '' }}
            </p>
        </div>

        {{-- Sélecteur de fichiers (sans name : sert uniquement à choisir) --}}
        <input type="file" data-picker accept=".png,.jpg,.jpeg,.gif,.webp" {{ $multiple ? 'multiple' : '' }} class="hidden">
        {{-- Vrai champ du formulaire, rempli par le JS --}}
        <input type="file" name="{{ $inputName }}" data-target {{ $multiple ? 'multiple' : '' }} class="hidden">

        <p data-msg class="hidden text-xs text-red-600 mt-2 whitespace-pre-line"></p>

        @if($serverErrors->isNotEmpty())
            <div class="mt-2 text-xs text-red-600">
                @foreach($serverErrors as $message)
                    <p>{{ $message }}</p>
                @endforeach
                <p class="text-admin-400 mt-1">Après une erreur, les images doivent être sélectionnées de nouveau.</p>
            </div>
        @endif
    </div>

    {{-- Aperçu des fichiers choisis --}}
    <div data-preview-container class="hidden p-4 border-t border-admin-100 bg-amber-50/50">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-xs font-semibold text-admin-600 uppercase tracking-wider">
                {{ $multiple ? 'À ajouter' : 'Nouvelle image' }} (<span data-preview-count>0</span>)
                <span class="normal-case font-normal text-admin-400">— envoyé à l'enregistrement du formulaire</span>
            </h4>
            <button type="button" data-clear-all class="text-xs text-red-500 hover:text-red-700">Tout effacer</button>
        </div>
        <div data-preview-grid class="grid grid-cols-4 md:grid-cols-6 gap-2"></div>
    </div>
</div>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ALLOWED = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];

    document.querySelectorAll('[data-img-uploader]').forEach(function (box) {
        const multiple = box.dataset.multiple === '1';
        const max = parseInt(box.dataset.max, 10);
        const maxMb = parseFloat(box.dataset.maxMb);
        const maxBytes = maxMb * 1024 * 1024;

        const dropzone = box.querySelector('[data-dropzone]');
        const picker = box.querySelector('[data-picker]');
        const target = box.querySelector('[data-target]');
        const msg = box.querySelector('[data-msg]');
        const previewContainer = box.querySelector('[data-preview-container]');
        const previewGrid = box.querySelector('[data-preview-grid]');
        const previewCount = box.querySelector('[data-preview-count]');
        const clearAll = box.querySelector('[data-clear-all]');

        let pending = [];

        function say(text) {
            msg.textContent = text || '';
            msg.classList.toggle('hidden', !text);
        }

        function add(files) {
            say('');
            const ok = [], problems = [];
            files.forEach(function (f) {
                if (!ALLOWED.includes(f.type)) problems.push(f.name + ' : format non accepté (JPG, PNG, GIF, WebP).');
                else if (f.size > maxBytes) problems.push(f.name + ' : dépasse ' + maxMb + ' Mo.');
                else ok.push(f);
            });

            if (!multiple) {
                if (ok.length) pending = [ok[ok.length - 1]];     // une seule image : la dernière choisie
            } else {
                const room = max - pending.length;
                if (ok.length > room) problems.push('Maximum ' + max + ' images : les suivantes ont été ignorées.');
                pending = pending.concat(ok.slice(0, Math.max(room, 0)));
            }

            if (problems.length) say(problems.join('\n'));
            sync();
        }

        // Injecte les fichiers choisis dans le vrai champ du formulaire
        function sync() {
            const dt = new DataTransfer();
            pending.forEach(function (f) { dt.items.add(f); });
            target.files = dt.files;
            render();
        }

        function render() {
            previewGrid.querySelectorAll('img').forEach(function (i) { URL.revokeObjectURL(i.src); });
            previewGrid.innerHTML = '';

            if (!pending.length) { previewContainer.classList.add('hidden'); return; }
            previewContainer.classList.remove('hidden');
            previewCount.textContent = pending.length;

            pending.forEach(function (file, index) {
                const tile = document.createElement('div');
                tile.className = 'relative aspect-square bg-admin-50 rounded border border-admin-200 overflow-hidden group';
                tile.innerHTML =
                    '<img class="w-full h-full object-cover" alt="">' +
                    '<button type="button" aria-label="Retirer" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full opacity-100 md:opacity-0 md:group-hover:opacity-100 transition">' +
                        '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' +
                    '</button>' +
                    '<div class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-[9px] p-1 truncate"></div>';
                tile.querySelector('img').src = URL.createObjectURL(file);
                tile.querySelector('div').textContent = file.name;
                tile.querySelector('button').addEventListener('click', function () {
                    pending.splice(index, 1);
                    say('');
                    sync();
                });
                previewGrid.appendChild(tile);
            });
        }

        dropzone.addEventListener('click', function () { picker.click(); });
        picker.addEventListener('change', function (e) { add(Array.from(e.target.files)); picker.value = ''; });

        dropzone.addEventListener('dragover', function (e) { e.preventDefault(); dropzone.classList.add('border-amber-400', 'bg-amber-50'); });
        dropzone.addEventListener('dragleave', function () { dropzone.classList.remove('border-amber-400', 'bg-amber-50'); });
        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('border-amber-400', 'bg-amber-50');
            add(Array.from(e.dataTransfer.files));
        });

        clearAll.addEventListener('click', function () { pending = []; say(''); sync(); });
    });
});
</script>
@endpush
@endonce