{{-- resources/views/public/home.blade.php --}}
@extends('layouts.app')

@section('title', 'AluStock — Catalogue de référence aluminium industriel')

{{-- ============================================================
     HERO PRINCIPAL (plein écran avec pattern)
     ============================================================ --}}
@section('hero')
<div class="relative min-h-[90vh] flex items-center overflow-hidden">
    
    {{-- Pattern de fond sobre --}}
    <div class="absolute inset-0 bg-ink-900">
        <div class="absolute inset-0 opacity-10"
             style="background-image: url(&quot;data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
        </div>
        {{-- Dégradé subtil --}}
        <div class="absolute inset-0 bg-gradient-to-br from-ink-950/80 via-ink-900/50 to-ink-800/30"></div>
    </div>

    {{-- Contenu du hero --}}
    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            {{-- Texte --}}
            <div class="text-white" data-aos="fade-right">
                <span class="inline-block text-xs font-semibold uppercase tracking-widest text-amber-400 mb-3 border border-amber-400/30 px-3 py-1 rounded-full">
                    Catalogue de référence industriel
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                    Aluminium industriel,<br>
                    <span class="text-amber-400">profilés et fixations</span>
                </h1>
                <p class="mt-4 text-lg text-ink-300 max-w-lg leading-relaxed">
                    Plus de 18 910 références documentées — profilés T-slot, tôles, visserie,
                    connecteurs et extrusions sur mesure. Fiches techniques EN disponibles pour chaque produit.
                </p>

                {{-- Barre de recherche --}}
                <form action="{{ route('search.index') }}" method="GET" class="mt-8 flex max-w-lg">
                    <input type="text"
                           name="q"
                           placeholder="Référence, alliage, dimension..."
                           class="flex-1 px-4 py-3.5 rounded-l-lg bg-white/10 border border-white/10 text-white placeholder-ink-400 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                    <button type="submit" class="px-6 py-3.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-r-lg transition shadow-lg shadow-amber-600/25 hover:shadow-amber-600/40 glow">
                        Rechercher
                    </button>
                </form>

                {{-- Stats rapides --}}
                <div class="flex flex-wrap gap-6 mt-6 text-sm text-ink-300">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span class="font-medium text-white" id="stat-references">18 910</span> références
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span class="font-medium text-white" id="stat-categories">6</span> catégories
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        <span class="font-medium text-white" id="stat-gammes">4</span> gammes
                    </span>
                </div>
            </div>

            {{-- Placeholder visuel / illustration --}}
            <div class="hidden lg:flex items-center justify-center" data-aos="fade-left">
                <div class="relative w-full max-w-md aspect-square">
                    {{-- Cercle décoratif --}}
                    <div class="absolute inset-0 border-2 border-amber-400/20 rounded-full animate-pulse-slow"></div>
                    <div class="absolute inset-8 border border-amber-400/10 rounded-full"></div>
                    <div class="absolute inset-16 border border-amber-400/5 rounded-full"></div>
                    
                    {{-- Icône centrale --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-32 h-32 bg-amber-500/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-amber-400/20">
                            <svg class="w-16 h-16 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Points décoratifs --}}
                    <div class="absolute top-0 right-0 w-3 h-3 bg-amber-400/30 rounded-full"></div>
                    <div class="absolute bottom-0 left-0 w-4 h-4 bg-amber-400/20 rounded-full"></div>
                    <div class="absolute top-1/2 -left-2 w-2 h-2 bg-amber-400/20 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Curseur de défilement --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-ink-400 animate-bounce">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</div>
@endsection

{{-- ============================================================
     CONTENU DE LA PAGE
     ============================================================ --}}
@section('content')
<div>

    {{-- ============================================================
         SECTION : CATÉGORIES EN VEDETTE
         ============================================================ --}}
    <section class="py-16" data-aos="fade-up">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-widest text-amber-600">
                    Parcourir par
                </h2>
                <h3 class="text-2xl font-bold text-ink-900 mt-1">Catégories d'ouvrages</h3>
            </div>
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center text-sm font-medium text-amber-700 hover:text-amber-800 transition group">
                Voir toutes les catégories
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredCategories as $category)
                <a href="{{ route('ouvrages.index', ['categorie' => $category->slug]) }}"
                   class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300 flex flex-col hover:-translate-y-1">
                    
                    {{-- Icône / Image --}}
                    <div class="h-24 bg-gradient-to-br from-ink-50 to-ink-100 flex items-center justify-center group-hover:from-amber-50 group-hover:to-amber-100 transition-colors duration-300">
                        <div class="w-14 h-14 rounded-full bg-ink-200/50 group-hover:bg-amber-200/50 flex items-center justify-center text-2xl transition-colors duration-300">
                            {{ $category->icone ?? '📦' }}
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="text-base font-semibold text-ink-900 group-hover:text-amber-700 transition">
                                {{ $category->nom }}
                            </h4>
                            <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800">
                                {{ number_format($category->ouvrages_count ?? 0, 0, ',', ' ') }}
                            </span>
                        </div>
                        @if($category->description)
                            <p class="text-sm text-ink-500 mt-1 line-clamp-2">
                                {{ Str::limit($category->description, 60) }}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                @for($i = 0; $i < 4; $i++)
                    <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-5 text-center text-ink-400">
                        <p class="text-sm">Catégorie à venir</p>
                    </div>
                @endfor
            @endforelse
        </div>
    </section>

    {{-- Séparateur décoratif --}}
    <div class="relative py-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-ink-200"></div>
        </div>
        <div class="relative flex justify-center">
            <span class="bg-ink-50 px-4 text-ink-300 text-sm">✦</span>
        </div>
    </div>

    {{-- ============================================================
         SECTION : GAMMES EN VEDETTE
         ============================================================ --}}
    <section class="py-16" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xs font-semibold uppercase tracking-widest text-amber-600">
                    Parcourir par
                </h2>
                <h3 class="text-2xl font-bold text-ink-900 mt-1">Gammes de profilés</h3>
            </div>
            <a href="{{ route('gammes.index') }}" 
               class="inline-flex items-center text-sm font-medium text-amber-700 hover:text-amber-800 transition group">
                Voir toutes les gammes
                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredGammes as $gamme)
                <a href="{{ route('ouvrages.index', ['gamme' => $gamme->slug]) }}"
                   class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-ink-200 hover:border-amber-300 flex flex-col hover:-translate-y-1">
                    
                    {{-- Image --}}
                    <div class="h-32 bg-ink-100 overflow-hidden">
                        @if($gamme->image_cover)
                            <img src="{{ asset('storage/' . $gamme->image_cover) }}"
                                 alt="{{ $gamme->nom }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-ink-300 bg-gradient-to-br from-ink-100 to-ink-200">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <h4 class="text-base font-semibold text-ink-900 group-hover:text-amber-700 transition">
                                {{ $gamme->nom }}
                            </h4>
                            <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-800">
                                {{ number_format($gamme->ouvrages_count ?? 0, 0, ',', ' ') }}
                            </span>
                        </div>
                        @if($gamme->description)
                            <p class="text-sm text-ink-500 mt-1 line-clamp-2">
                                {{ Str::limit($gamme->description, 60) }}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
                @for($i = 0; $i < 4; $i++)
                    <div class="bg-white rounded-xl shadow-sm border border-ink-200 p-5 text-center text-ink-400">
                        <p class="text-sm">Gamme à venir</p>
                    </div>
                @endfor
            @endforelse
        </div>
    </section>

    {{-- ============================================================
         SECTION : STATISTIQUES (animées)
         ============================================================ --}}
    <section class="py-16" data-aos="fade-up" data-aos-delay="200">
        <div class="bg-gradient-to-br from-ink-900 to-ink-800 rounded-2xl p-8 md:p-12 text-white relative overflow-hidden">
            
            {{-- Pattern léger --}}
            <div class="absolute inset-0 opacity-5"
                 style="background-image: url(&quot;data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);">
            </div>

            <div class="relative grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-amber-400" 
                         data-count="18910">0</div>
                    <p class="text-ink-300 text-sm mt-2 uppercase tracking-wider">Références</p>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-amber-400" 
                         data-count="{{ $totalCategories ?? 6 }}">0</div>
                    <p class="text-ink-300 text-sm mt-2 uppercase tracking-wider">Catégories</p>
                </div>
                <div>
                    <div class="text-4xl md:text-5xl font-bold text-amber-400" 
                         data-count="{{ $totalGammes ?? 4 }}">0</div>
                    <p class="text-ink-300 text-sm mt-2 uppercase tracking-wider">Gammes</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         SECTION : CTA FINAL
         ============================================================ --}}
    <section class="py-12 text-center" data-aos="fade-up" data-aos-delay="300">
        <a href="#" class="inline-flex items-center px-8 py-4 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-lg transition shadow-lg shadow-amber-600/25 hover:shadow-amber-600/40 glow">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Télécharger le catalogue PDF
        </a>
        <p class="text-ink-400 text-sm mt-3">Fiches techniques EN disponibles pour chaque produit</p>
    </section>

</div>
@endsection

{{-- ============================================================
     SCRIPTS : Animations et compteurs
     ============================================================ --}}
@push('scripts')
{{-- AOS (Animate On Scroll) --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Initialiser AOS
        AOS.init({
            duration: 600,
            once: true,
            offset: 80,
            easing: 'ease-out-cubic'
        });

        // 2. Compteur animé
        const counters = document.querySelectorAll('[data-count]');
        
        const animateCounter = (el) => {
            const target = parseInt(el.getAttribute('data-count'));
            const duration = 2000;
            const startTime = performance.now();
            
            const updateCounter = (currentTime) => {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Easing easeOutQuart
                const eased = 1 - Math.pow(1 - progress, 4);
                const current = Math.round(eased * target);
                
                el.textContent = current.toLocaleString();
                
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    el.textContent = target.toLocaleString();
                }
            };
            
            requestAnimationFrame(updateCounter);
        };

        // Observer pour déclencher les compteurs au scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    animateCounter(el);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    });
</script>

<style>
    /* Animation de glow pour les boutons */
    .glow {
        position: relative;
    }
    .glow::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: inherit;
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.3), rgba(245, 158, 11, 0.1));
        filter: blur(12px);
        z-index: -1;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .glow:hover::after {
        opacity: 1;
    }

    /* Pulse lente pour les cercles décoratifs */
    @keyframes pulse-slow {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }
    .animate-pulse-slow {
        animation: pulse-slow 4s ease-in-out infinite;
    }

    /* Animation de défilement */
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>
@endpush