{{-- resources/views/partials/vitrine-nav.blade.php --}}
{{-- Le comportement (fond au scroll, lien actif, menu mobile) est dans public/js/vitrine.js --}}

<div id="scroll-progress" aria-hidden="true"></div>

<nav id="navbar" class="fixed top-0 left-0 right-0 z-50" aria-label="Navigation principale">
    <div class="container mx-auto max-w-7xl px-4">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('vitrine.index') }}" class="flex items-center" aria-label="{{ $brandName }} — accueil">
                <span class="text-2xl font-bold text-white tracking-tight">
                    {{ config('vitrine.brand.first') }}<span class="text-accent-500">{{ config('vitrine.brand.second') }}</span>
                </span>
            </a>

            {{-- Desktop --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="#accueil" class="nav-link text-sm font-medium">Accueil</a>
                <a href="#a-propos" class="nav-link text-sm font-medium">À propos</a>
                <a href="#realisations" class="nav-link text-sm font-medium">Réalisations</a>
                <a href="#contact" class="nav-link text-sm font-medium">Contact</a>
                <a href="#contact" class="btn btn-primary btn-shine px-4 py-2 text-sm">
                    <i class="fas fa-paper-plane"></i> Demander un devis
                </a>
            </div>

            {{-- Bouton mobile --}}
            <button id="mobile-menu-button" type="button" class="md:hidden text-white hover:text-accent-400 transition-colors"
                    aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        {{-- Menu mobile --}}
        <div id="mobile-menu" class="md:hidden hidden py-4 border-t border-white/10">
            <div class="flex flex-col space-y-4">
                <a href="#accueil" class="nav-link text-sm font-medium">Accueil</a>
                <a href="#a-propos" class="nav-link text-sm font-medium">À propos</a>
                <a href="#realisations" class="nav-link text-sm font-medium">Réalisations</a>
                <a href="#contact" class="nav-link text-sm font-medium">Contact</a>
                <a href="#contact" class="btn btn-primary px-4 py-2 text-sm">
                    <i class="fas fa-paper-plane"></i> Demander un devis
                </a>
            </div>
        </div>
    </div>
</nav>