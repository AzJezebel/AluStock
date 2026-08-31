{{-- resources/views/public/vitrine/index.blade.php --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AluStock - Distributeur industriel d'aluminium, de profilés et de fixations. Plus de 18 910 références documentées.">
    <title>AluStock - Distributeur industriel d'aluminium</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        ink: {
                            50:  '#f6f7f8',
                            100: '#e9ebee',
                            200: '#d3d7dd',
                            300: '#aab1bb',
                            400: '#7c8492',
                            500: '#5b6472',
                            600: '#434b57',
                            700: '#313842',
                            800: '#20252d',
                            900: '#14171c',
                            950: '#0a0c0f',
                        },
                        amber: {
                            50:  '#faf6ee',
                            100: '#f1e3c8',
                            200: '#e3c78e',
                            300: '#d1a866',
                            400: '#bb8d47',
                            500: '#a97a3a',
                            600: '#8f6530',
                            700: '#735026',
                            800: '#573d1e',
                            900: '#3c2a15',
                            950: '#241a0e',
                        },
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- AOS.js -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --ink-50: #f6f7f8;
            --ink-100: #e9ebee;
            --ink-200: #d3d7dd;
            --ink-300: #aab1bb;
            --ink-400: #7c8492;
            --ink-500: #5b6472;
            --ink-600: #434b57;
            --ink-700: #313842;
            --ink-800: #20252d;
            --ink-900: #14171c;
            --ink-950: #0a0c0f;
        }

        .hero {
            background: var(--ink-950);
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .btn-flat {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .card-hover {
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .card-hover:hover {
            box-shadow: 0 4px 20px rgba(10, 12, 15, 0.08);
        }

        .counter {
            font-variant-numeric: tabular-nums;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--ink-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--ink-400);
            border-radius: 4px;
        }

        /* Entrée du hero — amplitude très réduite */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-animate {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .hero-animate-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .hero-animate-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .hero-animate-delay-3 { animation-delay: 0.3s; opacity: 0; }

        /* AOS — amplitude de déplacement très réduite (au lieu des ~100px par défaut) */
        [data-aos="fade-up"] {
            transform: translate3d(0, 10px, 0) !important;
        }
        [data-aos="fade-up"].aos-animate {
            transform: translate3d(0, 0, 0) !important;
        }
    </style>
</head>
<body class="bg-white">

    <!-- Navigation -->
    @include('partials.vitrine-nav')

    <!-- 1. Hero Principal -->
    <section class="hero min-h-screen flex items-center relative">
        <div class="hero-content container mx-auto px-4 py-20">
            <div class="max-w-4xl mx-auto text-center">

                <div class="hero-animate hero-animate-delay-1 mb-6">
                    <span class="inline-flex items-center gap-2 text-amber-400 text-xs font-semibold tracking-widest uppercase">
                        <span class="w-6 h-px bg-amber-400"></span>
                        Distributeur industriel depuis 1995
                        <span class="w-6 h-px bg-amber-400"></span>
                    </span>
                </div>

                <h1 class="hero-animate hero-animate-delay-1 text-5xl md:text-7xl font-extrabold text-white leading-tight mb-6">
                    L'aluminium
                    <span class="text-amber-400">à portée de main</span>
                </h1>

                <p class="hero-animate hero-animate-delay-2 text-xl md:text-2xl text-ink-300 mb-10 max-w-2xl mx-auto">
                    Plus de 18 910 références documentées pour tous vos projets d'architecture et d'industrie
                </p>

                <div class="hero-animate hero-animate-delay-2 max-w-2xl mx-auto mb-12">
                    <form action="{{ route('catalogue.index') }}" method="GET" class="relative">
                        <input
                            type="text"
                            name="search"
                            placeholder="Rechercher un profilé, une référence, une catégorie..."
                            class="w-full px-6 py-4 pr-36 rounded-none bg-white/5 border border-white/10 text-white placeholder-ink-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400/40 transition-colors"
                        >
                        <button type="submit" class="btn-flat absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-ink-950 font-semibold rounded-none">
                            <i class="fas fa-search mr-2"></i>
                            Rechercher
                        </button>
                    </form>
                </div>

                <div class="hero-animate hero-animate-delay-3 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto">
                    <div class="bg-white/5 rounded-none p-4 border border-white/10">
                        <div class="text-3xl font-bold text-amber-400 counter" data-target="{{ $stats['references'] }}">0</div>
                        <div class="text-sm text-ink-400 mt-1">Références</div>
                    </div>
                    <div class="bg-white/5 rounded-none p-4 border border-white/10">
                        <div class="text-3xl font-bold text-amber-400 counter" data-target="{{ $stats['categories'] }}">0</div>
                        <div class="text-sm text-ink-400 mt-1">Catégories</div>
                    </div>
                    <div class="bg-white/5 rounded-none p-4 border border-white/10">
                        <div class="text-3xl font-bold text-amber-400 counter" data-target="{{ $stats['gammes'] }}">0</div>
                        <div class="text-sm text-ink-400 mt-1">Gammes</div>
                    </div>
                    <div class="bg-white/5 rounded-none p-4 border border-white/10">
                        <div class="text-3xl font-bold text-amber-400 counter" data-target="{{ $stats['ouvrages'] }}">0</div>
                        <div class="text-sm text-ink-400 mt-1">Réalisations</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10">
            <i class="fas fa-chevron-down text-white/30 text-xl"></i>
        </div>
    </section>

    <!-- 2. Section À propos / Chiffres clés -->
    <section class="py-20 px-4 bg-white" data-aos="fade-up">
        <div class="container mx-auto max-w-6xl">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 text-amber-700 text-xs font-semibold tracking-widest uppercase mb-4">
                    <span class="w-6 h-px bg-amber-700"></span>
                    À propos
                    <span class="w-6 h-px bg-amber-700"></span>
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-ink-950 mb-4">
                    L'excellence <span class="text-amber-600">industrielle</span>
                </h2>
                <p class="text-xl text-ink-600 max-w-3xl mx-auto">
                    AluStock est votre partenaire de confiance pour la distribution d'aluminium et de profilés techniques.
                    Nous accompagnons les professionnels dans leurs projets les plus exigeants.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-none bg-ink-50 hover:bg-ink-100 transition-colors" data-aos="fade-up" data-aos-delay="60">
                    <div class="w-14 h-14 mx-auto mb-4 bg-amber-500/10 rounded-none flex items-center justify-center">
                        <i class="fas fa-industry text-xl text-amber-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-ink-950 mb-2">Qualité certifiée</h3>
                    <p class="text-ink-600">Des produits conformes aux normes EN avec des fiches techniques complètes</p>
                </div>
                <div class="text-center p-6 rounded-none bg-ink-50 hover:bg-ink-100 transition-colors" data-aos="fade-up" data-aos-delay="120">
                    <div class="w-14 h-14 mx-auto mb-4 bg-amber-500/10 rounded-none flex items-center justify-center">
                        <i class="fas fa-cubes text-xl text-amber-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-ink-950 mb-2">Stock permanent</h3>
                    <p class="text-ink-600">Plus de 18 000 références disponibles pour une livraison rapide</p>
                </div>
                <div class="text-center p-6 rounded-none bg-ink-50 hover:bg-ink-100 transition-colors" data-aos="fade-up" data-aos-delay="180">
                    <div class="w-14 h-14 mx-auto mb-4 bg-amber-500/10 rounded-none flex items-center justify-center">
                        <i class="fas fa-tools text-xl text-amber-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-ink-950 mb-2">Expertise technique</h3>
                    <p class="text-ink-600">Une équipe d'experts pour vous conseiller dans vos choix techniques</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================================
         3. Nos réalisations en vedette
         — section agrandie : premier ouvrage en grand format (mise
           en avant éditoriale), le reste en grille secondaire
         ============================================================ --}}
    <section class="py-28 px-4 bg-ink-50" data-aos="fade-up">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-20">
                <span class="inline-flex items-center gap-2 text-amber-700 text-xs font-semibold tracking-widest uppercase mb-4">
                    <span class="w-6 h-px bg-amber-700"></span>
                    Portfolio
                    <span class="w-6 h-px bg-amber-700"></span>
                </span>
                <h2 class="text-4xl md:text-6xl font-bold text-ink-950 mb-4">
                    Nos <span class="text-amber-600">réalisations</span> en vedette
                </h2>
                <p class="text-xl text-ink-600 max-w-3xl mx-auto">
                    Découvrez une sélection de nos plus beaux ouvrages réalisés avec des profilés aluminium
                </p>
            </div>

            @if($featuredOuvrages->isNotEmpty())
                <div class="space-y-12">

                    {{-- Ouvrage phare — grand format --}}
                    @php $premier = $featuredOuvrages->first(); @endphp
                    <div class="card-hover grid md:grid-cols-2 bg-white border border-ink-200" data-aos="fade-up">
                        <div class="h-80 md:h-auto min-h-[24rem] bg-gradient-to-br from-ink-200 to-ink-300 relative">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-building text-7xl text-ink-400"></i>
                            </div>
                            <div class="absolute top-6 left-6">
                                <span class="px-3 py-1 bg-amber-600 text-white text-xs font-semibold">
                                    {{ $premier->categorie->nom ?? 'Non catégorisé' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-10 md:p-12 flex flex-col justify-center">
                            <h3 class="text-2xl md:text-3xl font-bold text-ink-950 mb-4">{{ $premier->titre }}</h3>
                            <p class="text-ink-600 mb-8">{{ $premier->description ?? 'Ouvrage réalisé avec des profilés aluminium de qualité supérieure' }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-ink-500">
                                    <i class="fas fa-tag mr-1"></i> {{ $premier->gamme->nom ?? 'Gamme standard' }}
                                </span>
                                <a href="#" class="text-amber-700 hover:text-amber-800 font-semibold text-sm transition-colors">
                                    Voir plus <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Reste de la sélection — grille secondaire --}}
                    @if($featuredOuvrages->count() > 1)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            @foreach($featuredOuvrages->skip(1) as $ouvrage)
                                <div class="card-hover bg-white border border-ink-200" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                                    <div class="h-64 bg-gradient-to-br from-ink-200 to-ink-300 flex items-center justify-center relative">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <i class="fas fa-building text-6xl text-ink-400"></i>
                                        </div>
                                        <div class="absolute top-4 right-4">
                                            <span class="px-3 py-1 bg-amber-600 text-white text-xs font-semibold">
                                                {{ $ouvrage->categorie->nom ?? 'Non catégorisé' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-7">
                                        <h3 class="text-xl font-bold text-ink-950 mb-2">{{ $ouvrage->titre }}</h3>
                                        <p class="text-ink-600 text-sm mb-4 line-clamp-2">{{ $ouvrage->description ?? 'Ouvrage réalisé avec des profilés aluminium de qualité supérieure' }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-ink-500">
                                                <i class="fas fa-tag mr-1"></i> {{ $ouvrage->gamme->nom ?? 'Gamme standard' }}
                                            </span>
                                            <a href="#" class="text-amber-700 hover:text-amber-800 font-semibold text-sm transition-colors">
                                                Voir plus <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-ink-500">Aucun ouvrage disponible pour le moment</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================================
         4. Nos gammes / catégories
         — section très compacte : simple bandeau de tuiles,
           sans description, pour ne pas concurrencer le portfolio
         ============================================================ --}}
    <section class="py-12 px-4 bg-white border-t border-ink-100" data-aos="fade-up">
        <div class="container mx-auto max-w-7xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-sm font-semibold uppercase tracking-widest text-ink-500">
                    Gammes &amp; catégories
                </h2>
                <a href="{{ route('catalogue.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-800 transition-colors">
                    Tout voir <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($gammes as $gamme)
                <a href="#" class="group flex items-center gap-2 p-3 border border-ink-200 hover:border-amber-400 transition-colors" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 30 }}">
                    <div class="w-8 h-8 shrink-0 bg-ink-950 flex items-center justify-center">
                        <i class="fas fa-cube text-amber-400 text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-ink-800 group-hover:text-amber-700 transition-colors truncate">
                        {{ $gamme->nom }}
                    </span>
                </a>
                @endforeach

                @foreach($categories as $categorie)
                <a href="#" class="group flex items-center gap-2 p-3 border border-ink-200 hover:border-amber-400 transition-colors" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration + 4) * 30 }}">
                    <div class="w-8 h-8 shrink-0 bg-amber-700 flex items-center justify-center">
                        <i class="fas fa-door-open text-white text-xs"></i>
                    </div>
                    <span class="text-sm font-medium text-ink-800 group-hover:text-amber-700 transition-colors truncate">
                        {{ $categorie->nom }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. Section CTA final -->
    <section class="relative py-24 px-4 bg-ink-950" data-aos="fade-up">
        <div class="container mx-auto max-w-4xl text-center relative z-10">
            <span class="inline-flex items-center gap-2 text-amber-400 text-xs font-semibold tracking-widest uppercase mb-6">
                <span class="w-6 h-px bg-amber-400"></span>
                Catalogue technique
                <span class="w-6 h-px bg-amber-400"></span>
            </span>
            <h2 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Accédez à notre <span class="text-amber-400">catalogue complet</span>
            </h2>
            <p class="text-xl text-ink-300 mb-10 max-w-2xl mx-auto">
                Plus de 18 910 références documentées avec fiches techniques EN disponibles en téléchargement
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('catalogue.index') }}" class="btn-flat px-8 py-4 bg-amber-500 hover:bg-amber-600 text-ink-950 font-semibold rounded-none text-lg inline-flex items-center justify-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Accéder au catalogue
                </a>
                <a href="#" class="btn-flat px-8 py-4 bg-white/5 hover:bg-white/10 text-white font-semibold rounded-none text-lg border border-white/15 inline-flex items-center justify-center">
                    <i class="fas fa-phone mr-2"></i>
                    Nous contacter
                </a>
            </div>
            <p class="text-ink-400 text-sm mt-6">
                <i class="fas fa-check-circle text-amber-400 mr-1"></i>
                Fiches techniques EN disponibles pour chaque produit
            </p>
        </div>
    </section>

    <!-- 6. Footer -->
    <footer class="bg-ink-950 text-ink-300 border-t border-ink-800">
        <div class="container mx-auto max-w-7xl px-4 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-4">
                        Alu<span class="text-amber-500">Stock</span>
                    </h3>
                    <p class="text-sm mb-4">Distributeur industriel d'aluminium, de profilés et de fixations depuis 1995</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-ink-400 hover:text-amber-400 transition-colors">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-ink-400 hover:text-amber-400 transition-colors">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-ink-400 hover:text-amber-400 transition-colors">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-ink-400 hover:text-amber-400 transition-colors">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Navigation</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Accueil</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Catalogue</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Réalisations</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Gammes</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Gamme 45</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Gamme 55</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Gamme Structure</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition-colors">Gamme Design</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fas fa-phone mr-2 text-amber-400"></i> +33 (0)1 23 45 67 89</li>
                        <li><i class="fas fa-envelope mr-2 text-amber-400"></i> contact@alustock.fr</li>
                        <li><i class="fas fa-map-marker-alt mr-2 text-amber-400"></i> 123 Avenue de l'Industrie</li>
                        <li><i class="fas fa-map-pin mr-2 text-amber-400"></i> 75001 Paris, France</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-ink-800 mt-12 pt-8 text-center text-sm text-ink-500">
                <p>&copy; {{ date('Y') }} AluStock. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Amplitude et durée des animations au scroll très réduites
        AOS.init({
            duration: 350,
            once: true,
            offset: 40,
            easing: 'ease-out'
        });

        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.counter');

            const animateCounter = (counter) => {
                const target = parseInt(counter.dataset.target);
                const duration = 1500;
                const step = Math.max(1, Math.floor(target / 60));
                let current = 0;

                const updateCounter = () => {
                    current += step;
                    if (current >= target) {
                        counter.textContent = target.toLocaleString();
                        return;
                    }
                    counter.textContent = current.toLocaleString();
                    requestAnimationFrame(updateCounter);
                };

                updateCounter();
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        animateCounter(counter);
                        observer.unobserve(counter);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => observer.observe(counter));
        });
    </script>
</body>
</html>