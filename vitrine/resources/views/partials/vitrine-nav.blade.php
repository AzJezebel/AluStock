{{-- resources/views/partials/vitrine-nav.blade.php --}}

<nav class="fixed top-0 left-0 right-0 z-50 bg-ink-950/90 backdrop-blur-sm border-b border-white/10">
    <div class="container mx-auto max-w-7xl px-4">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="{{ route('vitrine.index') }}" class="flex items-center space-x-2">
                <span class="text-2xl font-bold text-white">
                    Alu<span class="text-amber-500">Stock</span>
                </span>
            </a>

            <!-- Navigation links - Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Accueil</a>
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Réalisations</a>
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Gammes</a>
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Contact</a>
                <a href="{{ route('catalogue.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-ink-950 font-semibold rounded-none text-sm transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Catalogue
                </a>
            </div>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="md:hidden text-white hover:text-amber-400 transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden py-4 border-t border-white/10">
            <div class="flex flex-col space-y-4">
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Accueil</a>
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Réalisations</a>
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Gammes</a>
                <a href="#" class="text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Contact</a>
                <a href="{{ route('catalogue.index') }}" class="inline-block px-4 py-2 bg-amber-500 hover:bg-amber-600 text-ink-950 font-semibold rounded-none text-sm transition-colors text-center">
                    <i class="fas fa-search mr-2"></i>
                    Catalogue
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        if (button && menu) {
            button.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });
        }
    });
</script>