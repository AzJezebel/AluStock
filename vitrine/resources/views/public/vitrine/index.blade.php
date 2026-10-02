{{-- resources/views/public/vitrine/index.blade.php --}}
@php
    $brandName = trim(config('vitrine.brand.first') . config('vitrine.brand.second'));
    $hero = config('vitrine.hero');

    // --- Ouvrages normalisés (placeholders si pas d'image, démo si base vide)
    $items = collect($featuredOuvrages ?? [])->values()
        ->map(fn ($o, $i) => \App\Support\Media::ouvrage($o, $i));
    $isDemo = false;
    if ($items->isEmpty() && config('vitrine.demo_when_empty')) {
        $items = collect(\App\Support\Media::demo());
        $isDemo = true;
    }
    $cats = $items->pluck('categorie')->filter()->unique()->values();

    // --- Hero
    // Images gérées depuis l'admin (SiteImages) ; sinon celles de config/vitrine.php ; sinon placeholders
    $heroImages = \App\Support\SiteImages::urls('hero') ?: \App\Support\Media::urls($hero['images'] ?? []);
    $aboutImage = \App\Support\SiteImages::first('about');
    $heroCount = count($heroImages) ?: 3;
    $titleWords1 = preg_split('/\s+/', trim($hero['title_1']));
    $titleWords2 = preg_split('/\s+/', trim($hero['title_2']));

    // --- Chiffres
    $years = max(1, now()->year - (int) config('vitrine.since'));
    $stats = collect([
        ['value' => $stats['ouvrages'] ?? $items->count(), 'label' => 'Réalisations'],
        ['value' => $stats['categories'] ?? $cats->count(), 'label' => 'Domaines'],
        ['value' => $years, 'label' => "Années d'expérience"],
    ])->filter(fn ($s) => (int) $s['value'] > 0)->values();

    // --- Bandeau défilant
    $marquee = collect($categories ?? [])->pluck('nom')
        ->merge($cats)->filter()->unique()->values();
    if ($marquee->count() < 5) {
        $marquee = $marquee->merge(config('vitrine.marquee'))->unique()->values();
    }

    // --- Données JSON pour la lightbox
    $lightboxData = $items->map(fn ($it) => \Illuminate\Support\Arr::only(
        $it, ['titre', 'description', 'categorie', 'lieu', 'annee', 'images']
    ))->values();
@endphp
<!DOCTYPE html>
<html lang="fr" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $brandName }} — {{ config('vitrine.tagline') }}">
    <title>{{ $brandName }} — Réalisations en aluminium</title>

    {{-- Évite le flash : active .js avant le rendu + applique la palette mémorisée --}}
    <script>
        document.documentElement.classList.replace('no-js', 'js');
        @if (config('vitrine.palette_switcher'))
        try {
            var s = JSON.parse(localStorage.getItem('vitrine.palette') || '{}'), d = document.documentElement;
            if (s.ink) d.dataset.ink = s.ink;
            if (s.accent) d.dataset.accent = s.accent;
            if (s.fx === false) d.dataset.fx = 'off';
        } catch (e) {}
        @endif
    </script>

    {{-- Palettes (variables CSS) — avant Tailwind --}}
    <link rel="stylesheet" href="{{ asset('css/palettes.css') }}">

    {{-- Tailwind CDN : les couleurs lisent les variables CSS, donc changent avec la palette --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const v = (name) => `rgb(var(--${name}) / <alpha-value>)`;
        const scale = (p) => Object.fromEntries([50,100,200,300,400,500,600,700,800,900,950].map(s => [s, v(`${p}-${s}`)]));
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], display: ['Fraunces', 'Georgia', 'serif'] },
                    colors: { ink: scale('ink'), accent: scale('accent'), onaccent: v('on-accent') },
                }
            }
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/vitrine.css') }}">
</head>
<body class="bg-white text-ink-900">

    @include('partials.vitrine-nav')

    {{-- =================================================================
         1. HERO — diaporama Ken Burns + aurore + grille + projecteur + grain
         ================================================================= --}}
    <section id="accueil" class="hero relative min-h-screen flex items-center overflow-hidden"
             data-interval="{{ $hero['interval'] ?? 6500 }}" style="--interval: {{ $hero['interval'] ?? 6500 }}ms;">

        {{-- Diaporama (images de config('vitrine.hero.images'), sinon placeholders) --}}
        <div class="absolute inset-0 -z-10" aria-hidden="true">
            @for ($i = 0; $i < $heroCount; $i++)
                <x-media-image :src="$heroImages[$i] ?? null" :seed="$i" label="" :icon="false" :eager="$i === 0"
                               class="hero-slide absolute inset-0 {{ $i === 0 ? 'is-active' : '' }}"
                               style="--kx: {{ $i % 2 ? '1.5%' : '-1.5%' }}; --ky: {{ $i % 3 ? '-1%' : '1%' }};" />
            @endfor
        </div>
        <div class="hero-overlay absolute inset-0 -z-10"></div>

        {{-- Effets décoratifs (désactivables depuis le panneau de palettes) --}}
        <div class="hero-fx absolute inset-0 -z-10 pointer-events-none overflow-hidden" aria-hidden="true">
            <div class="absolute -top-[10%] -left-[8%]"><div class="aurora-blob aurora-a"></div></div>
            <div class="absolute top-[30%] -right-[10%]"><div class="aurora-blob aurora-b"></div></div>
            <div class="absolute -bottom-[20%] left-[25%]"><div class="aurora-blob aurora-c"></div></div>
        </div>
        <div class="hero-fx hero-grid absolute inset-0 -z-10 pointer-events-none" aria-hidden="true"></div>
        <div class="hero-fx hero-spot absolute inset-0 -z-10 pointer-events-none" aria-hidden="true"></div>
        <div class="hero-fx hero-grain absolute inset-0 -z-10 pointer-events-none" aria-hidden="true"></div>

        {{-- Contenu --}}
        <div class="container mx-auto px-4 pt-28 pb-32 relative">
            <div class="max-w-5xl mx-auto text-center">

                <div class="hero-fade mb-8" style="--d: 100ms">
                    <span class="inline-flex items-center gap-3 text-accent-400 text-xs font-semibold tracking-[.25em] uppercase">
                        <span class="w-8 h-px bg-accent-400"></span>
                        {{ $hero['eyebrow'] }} · depuis {{ config('vitrine.since') }}
                        <span class="w-8 h-px bg-accent-400"></span>
                    </span>
                </div>

                <h1 class="text-5xl sm:text-6xl md:text-8xl font-extrabold text-white leading-[1.02] tracking-tight mb-8">
                    <span class="block">
                        @foreach ($titleWords1 as $w)
                            <span class="word"><span style="--i: {{ $loop->index }}">{{ $w }}</span></span>
                        @endforeach
                    </span>
                    <span class="block font-display italic font-medium text-accent-400">
                        @foreach ($titleWords2 as $w)
                            <span class="word"><span style="--i: {{ count($titleWords1) + $loop->index }}">{{ $w }}</span></span>
                        @endforeach
                    </span>
                </h1>

                <p class="hero-fade text-lg md:text-xl text-ink-200 mb-12 max-w-2xl mx-auto" style="--d: 900ms">
                    {{ $hero['subtitle'] }}
                </p>

                <div class="hero-fade flex flex-col sm:flex-row gap-4 justify-center mb-16" style="--d: 1100ms">
                    <a href="#realisations" class="btn btn-primary btn-shine px-8 py-4 text-lg">
                        Découvrir nos réalisations <i class="fas fa-arrow-down"></i>
                    </a>
                    <a href="#contact" class="btn btn-ghost px-8 py-4 text-lg">
                        <i class="fas fa-phone"></i> Nous contacter
                    </a>
                </div>

                @if ($stats->isNotEmpty())
                    <div class="hero-fade grid grid-cols-2 md:grid-cols-{{ min(4, $stats->count()) }} gap-4 max-w-3xl mx-auto" style="--d: 1300ms">
                        @foreach ($stats as $s)
                            <div class="stat-card p-4 text-center">
                                <div class="text-3xl font-bold text-accent-400 tabular-nums counter" data-target="{{ (int) $s['value'] }}">0</div>
                                <div class="text-xs uppercase tracking-widest text-ink-300 mt-1">{{ $s['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Indicateurs de diapositive --}}
        @if ($heroCount > 1)
            <div class="absolute bottom-8 right-6 md:right-10 z-10 hidden sm:flex items-center gap-4 text-xs text-ink-300 tracking-widest">
                <span id="hero-count" class="tabular-nums">01 / {{ str_pad($heroCount, 2, '0', STR_PAD_LEFT) }}</span>
                <div class="flex gap-2">
                    @for ($i = 0; $i < $heroCount; $i++)
                        <button type="button" class="hero-dot {{ $i === 0 ? 'is-active' : '' }}" aria-label="Image {{ $i + 1 }}"></button>
                    @endfor
                </div>
            </div>
        @endif

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 hidden md:block" aria-hidden="true">
            <div class="scroll-cue"></div>
        </div>
    </section>

    {{-- Bandeau défilant --}}
    <div class="marquee-wrap bg-accent-500 text-onaccent overflow-hidden border-y border-black/10" aria-hidden="true">
        <div class="marquee py-4">
            @for ($copy = 0; $copy < 2; $copy++)
                <div class="flex shrink-0 items-center">
                    @foreach ($marquee as $word)
                        <span class="px-8 text-sm font-semibold uppercase tracking-[.25em] whitespace-nowrap">{{ $word }}</span>
                        <i class="fas fa-diamond text-[.5rem] opacity-60"></i>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>

    {{-- =================================================================
         2. À PROPOS
         ================================================================= --}}
    <section id="a-propos" class="py-24 px-4 bg-white">
        <div class="container mx-auto max-w-7xl">
            <div class="grid lg:grid-cols-2 gap-14 items-center">

                <div class="reveal">
                    <span class="inline-flex items-center gap-3 text-accent-700 text-xs font-semibold tracking-[.25em] uppercase mb-5">
                        <span class="w-8 h-px bg-accent-700"></span> À propos
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold text-ink-950 leading-tight mb-6">
                        L'excellence <span class="font-display italic font-medium text-accent-600">artisanale</span>,
                        la rigueur de l'industrie
                    </h2>
                    <p class="text-lg text-ink-600 mb-10">
                        {{ $brandName }} conçoit et réalise des ouvrages en aluminium pour les professionnels
                        comme pour les particuliers. Chaque projet est étudié, fabriqué et posé avec le même
                        souci du détail.
                    </p>

                    <div class="space-y-4">
                        @foreach ([
                            ['fa-compass-drafting', 'Sur mesure', "Chaque ouvrage est dessiné pour son lieu, ses contraintes et son usage."],
                            ['fa-gem', 'Finitions soignées', "Thermolaquage, anodisation, brossage : une matière travaillée jusque dans le détail."],
                            ['fa-helmet-safety', 'Pose maîtrisée', "Des équipes qualifiées, du relevé de cotes à la réception du chantier."],
                        ] as [$icon, $title, $text])
                            <div class="value-card flex gap-4 p-5 reveal" style="--d: {{ $loop->index * 90 }}ms">
                                <div class="w-12 h-12 shrink-0 bg-accent-500/10 flex items-center justify-center">
                                    <i class="fas {{ $icon }} text-accent-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-ink-950">{{ $title }}</h3>
                                    <p class="text-sm text-ink-600">{{ $text }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="relative reveal" style="--d: 150ms">
                    <x-media-image :src="$aboutImage" :alt="'À propos de ' . $brandName" :seed="2" label="Photo atelier / équipe" class="aspect-[4/5] w-full" />
                    <div class="absolute -bottom-6 -left-4 md:-left-8 bg-ink-950 text-white p-6 shadow-2xl">
                        <div class="text-4xl font-bold text-accent-400 tabular-nums">{{ $years }}+</div>
                        <div class="text-xs uppercase tracking-widest text-ink-300">ans de savoir-faire</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =================================================================
         3. RÉALISATIONS — galerie filtrable + lightbox
         ================================================================= --}}
    <section id="realisations" class="py-28 px-4 bg-ink-50">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-14 reveal">
                <span class="inline-flex items-center gap-3 text-accent-700 text-xs font-semibold tracking-[.25em] uppercase mb-5">
                    <span class="w-8 h-px bg-accent-700"></span> Portfolio <span class="w-8 h-px bg-accent-700"></span>
                </span>
                <h2 class="text-4xl md:text-6xl font-bold text-ink-950 mb-5">
                    Nos <span class="font-display italic font-medium text-accent-600">réalisations</span>
                </h2>
                <p class="text-xl text-ink-600 max-w-3xl mx-auto">
                    Une sélection d'ouvrages livrés, du plus discret au plus ambitieux.
                </p>
                @if ($isDemo)
                    <p class="inline-block mt-5 px-3 py-1 text-xs font-semibold bg-accent-500/15 text-accent-800">
                        Données de démonstration — elles disparaissent dès qu'un ouvrage est publié
                    </p>
                @endif
            </div>

            @if ($items->isNotEmpty())
                @if ($cats->count() > 1)
                    <div class="flex flex-wrap justify-center gap-2 mb-10 reveal" role="group" aria-label="Filtrer par catégorie">
                        <button type="button" class="chip is-active" data-filter="all" aria-pressed="true">Tous</button>
                        @foreach ($cats as $cat)
                            <button type="button" class="chip" data-filter="{{ \Illuminate\Support\Str::slug($cat) }}" aria-pressed="false">{{ $cat }}</button>
                        @endforeach
                    </div>
                @endif

                <div class="gallery">
                    @foreach ($items as $it)
                        <article class="ouvrage reveal {{ $loop->first ? 'is-lead' : '' }}"
                                 style="--d: {{ min($loop->index, 5) * 70 }}ms"
                                 data-index="{{ $loop->index }}"
                                 data-cat="{{ \Illuminate\Support\Str::slug($it['categorie']) }}"
                                 tabindex="0" role="button" aria-label="Voir « {{ $it['titre'] }} »">

                            <x-media-image :src="$it['images'][0] ?? null" :alt="$it['titre']" :seed="$loop->index"
                                           class="absolute inset-0" />
                            <div class="ouvrage-shade"></div>

                            @if ($it['categorie'])
                                <span class="badge">{{ $it['categorie'] }}</span>
                            @endif

                            <div class="ouvrage-body">
                                <h3 class="font-bold leading-tight {{ $loop->first ? 'text-2xl md:text-4xl' : 'text-xl' }}">{{ $it['titre'] }}</h3>
                                @if ($it['description'])
                                    <p class="ouvrage-desc">{{ $it['description'] }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-xs text-ink-300">
                                        @if ($it['lieu'] || $it['annee'])<i class="fas fa-location-dot mr-1"></i>{{ collect([$it['lieu'], $it['annee']])->filter()->implode(' · ') }}@endif
                                    </span>
                                    <span class="ouvrage-more">Voir <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-ink-500">Aucun ouvrage disponible pour le moment</p>
                </div>
            @endif
        </div>
    </section>

    {{-- =================================================================
         4. CONTACT
         ================================================================= --}}
    <section id="contact" class="relative py-28 px-4 bg-ink-950 overflow-hidden">
        <div class="hero-fx absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="aurora-blob aurora-a absolute -top-1/3 left-1/4"></div>
        </div>
        <div class="container mx-auto max-w-4xl text-center relative z-10">
            <span class="inline-flex items-center gap-3 text-accent-400 text-xs font-semibold tracking-[.25em] uppercase mb-6 reveal">
                <span class="w-8 h-px bg-accent-400"></span> Votre projet <span class="w-8 h-px bg-accent-400"></span>
            </span>
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6 reveal">
                Parlons de votre <span class="font-display italic font-medium text-accent-400">prochain ouvrage</span>
            </h2>
            <p class="text-xl text-ink-300 mb-12 max-w-2xl mx-auto reveal">
                Décrivez-nous votre projet : nous revenons vers vous avec une étude et un devis détaillé.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-14 reveal">
                <a href="mailto:{{ config('vitrine.contact.email') }}" class="btn btn-primary btn-shine px-8 py-4 text-lg">
                    <i class="fas fa-envelope"></i> Écrire un message
                </a>
                <a href="tel:{{ preg_replace('/[^+\d]/', '', config('vitrine.contact.phone')) }}" class="btn btn-ghost px-8 py-4 text-lg">
                    <i class="fas fa-phone"></i> {{ config('vitrine.contact.phone') }}
                </a>
            </div>

            <div class="grid sm:grid-cols-3 gap-4 text-left reveal">
                <div class="stat-card p-5">
                    <i class="fas fa-envelope text-accent-400 mb-3"></i>
                    <div class="text-sm text-ink-300 break-words">{{ config('vitrine.contact.email') }}</div>
                </div>
                <div class="stat-card p-5">
                    <i class="fas fa-phone text-accent-400 mb-3"></i>
                    <div class="text-sm text-ink-300">{{ config('vitrine.contact.phone') }}</div>
                </div>
                <div class="stat-card p-5">
                    <i class="fas fa-location-dot text-accent-400 mb-3"></i>
                    <div class="text-sm text-ink-300">{{ config('vitrine.contact.address') }}<br>{{ config('vitrine.contact.city') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- =================================================================
         5. FOOTER
         ================================================================= --}}
    <footer class="bg-ink-950 text-ink-300 border-t border-ink-800">
        <div class="container mx-auto max-w-7xl px-4 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-4">
                        {{ config('vitrine.brand.first') }}<span class="text-accent-500">{{ config('vitrine.brand.second') }}</span>
                    </h3>
                    <p class="text-sm mb-5 max-w-xs">{{ config('vitrine.tagline') }}</p>
                    <div class="flex space-x-4">
                        @foreach (['linkedin', 'facebook', 'instagram', 'youtube'] as $social)
                            <a href="#" class="text-ink-400 hover:text-accent-400 transition-colors" aria-label="{{ ucfirst($social) }}">
                                <i class="fab fa-{{ $social }} text-xl"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#accueil" class="hover:text-accent-400 transition-colors">Accueil</a></li>
                        <li><a href="#a-propos" class="hover:text-accent-400 transition-colors">À propos</a></li>
                        <li><a href="#realisations" class="hover:text-accent-400 transition-colors">Réalisations</a></li>
                        <li><a href="#contact" class="hover:text-accent-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fas fa-phone mr-2 text-accent-400"></i> {{ config('vitrine.contact.phone') }}</li>
                        <li><i class="fas fa-envelope mr-2 text-accent-400"></i> {{ config('vitrine.contact.email') }}</li>
                        <li><i class="fas fa-location-dot mr-2 text-accent-400"></i> {{ config('vitrine.contact.address') }}, {{ config('vitrine.contact.city') }}</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-ink-800 mt-12 pt-8 text-center text-sm text-ink-500">
                <p>&copy; {{ date('Y') }} {{ $brandName }}. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    {{-- Lightbox --}}
    <div id="lightbox" hidden role="dialog" aria-modal="true" aria-label="Détail de la réalisation">
        <div class="lb-bar">
            <span id="lb-count" class="tabular-nums"></span>
            <button type="button" class="lb-close" aria-label="Fermer"><i class="fas fa-xmark"></i></button>
        </div>
        <div class="lb-stage">
            <button type="button" class="lb-btn lb-prev" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>
            <button type="button" class="lb-btn lb-next" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="lb-caption">
            <div class="lb-thumbs"></div>
            <div class="mt-4 flex items-center gap-3">
                <span id="lb-cat" class="text-xs font-bold uppercase tracking-widest text-accent-400"></span>
                <span id="lb-meta" class="text-xs text-ink-400"></span>
            </div>
            <h3 id="lb-title" class="text-2xl md:text-3xl font-bold mt-1"></h3>
            <p id="lb-desc" class="text-ink-300 mt-2"></p>
        </div>
    </div>

    <script type="application/json" id="ouvrages-data">{!! json_encode($lightboxData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>

    <button id="back-to-top" type="button" aria-label="Remonter en haut"><i class="fas fa-arrow-up"></i></button>

    <script src="{{ asset('js/vitrine.js') }}" defer></script>

    @if (config('vitrine.palette_switcher'))
        @include('partials.palette-switcher')
    @endif
</body>
</html>