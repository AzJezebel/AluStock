{{--
    resources/views/components/media-image.blade.php

    Usage : <x-media-image :src="$url" alt="..." :seed="$i" class="h-64" />

    - Le placeholder est TOUJOURS rendu dessous (thème-aware : il suit la palette active).
    - Si $src est vide, ou si le fichier est introuvable (onerror), seul le placeholder reste.
    - Si l'image se charge, elle apparaît en fondu par-dessus.
    - Donne toujours une taille au conteneur (h-64, aspect-video, absolute inset-0...).
--}}
@props(['src' => null, 'alt' => '', 'seed' => 0, 'label' => 'Photo à venir', 'icon' => true, 'eager' => false])

@php
    $seed = (int) $seed;
    $angle = [135, 205, 160, 235, 115, 255][$seed % 6];
    $px = [28, 72, 45, 80, 18, 60][$seed % 6];
    $py = [22, 30, 75, 70, 60, 18][$seed % 6];
    $cls = (string) $attributes->get('class', '');
    $positioned = \Illuminate\Support\Str::contains($cls, ['absolute', 'relative', 'fixed', 'sticky']);
@endphp

<div {{ $attributes->class(['media overflow-hidden', 'relative' => ! $positioned]) }}>
    <div class="ph absolute inset-0" style="--a: {{ $angle }}deg; --x: {{ $px }}%; --y: {{ $py }}%;" aria-hidden="true">
        <div class="ph-lines"></div>
        @if ($icon)
            <i class="fas fa-image ph-icon"></i>
        @endif
        @if ($label !== '')
            <span class="ph-label">{{ $label }}</span>
        @endif
    </div>

    @if ($src)
        <img src="{{ $src }}" alt="{{ $alt }}"
             loading="{{ $eager ? 'eager' : 'lazy' }}" decoding="async"
             class="media-img"
             onload="this.classList.add('is-loaded')" onerror="this.remove()">
    @endif
</div>
