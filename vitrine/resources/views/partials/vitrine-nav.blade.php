{{-- resources/views/partials/vitrine-nav.blade.php - Version améliorée --}}

<nav class="fixed top-0 left-0 right-0 z-50 bg-ink-950/90 backdrop-blur-sm border-b border-white/10 transition-all duration-300" id="navbar">
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
                <a href="#accueil" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium relative group">
                    Accueil
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#a-propos" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium relative group">
                    À propos
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#realisations" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium relative group">
                    Réalisations
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#gammes" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium relative group">
                    Gammes
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="#contact" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium relative group">
                    Contact
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-amber-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
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
                <a href="#accueil" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Accueil</a>
                <a href="#a-propos" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">À propos</a>
                <a href="#realisations" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Réalisations</a>
                <a href="#gammes" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Gammes</a>
                <a href="#contact" class="nav-link text-ink-300 hover:text-amber-400 transition-colors text-sm font-medium">Contact</a>
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
        // Mobile menu toggle
        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');

        if (button && menu) {
            button.addEventListener('click', function() {
                menu.classList.toggle('hidden');
            });

            // Fermer le menu en cliquant sur un lien
            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    menu.classList.add('hidden');
                });
            });
        }

        // Smooth scroll avec compensation de la navbar
        const navLinks = document.querySelectorAll('.nav-link');
        const navbar = document.getElementById('navbar');
        const navbarHeight = navbar ? navbar.offsetHeight : 64;

        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Ne pas interférer avec le lien "Catalogue"
                if (this.getAttribute('href') === '{{ route('catalogue.index') }}') {
                    return;
                }

                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId && targetId.startsWith('#')) {
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        const padding = 20;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight - padding;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // Highlight active section on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinksAll = document.querySelectorAll('.nav-link');

        function updateActiveLink() {
            let current = '';
            const scrollPosition = window.pageYOffset + navbarHeight + 100;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionBottom = sectionTop + section.offsetHeight;

                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    current = section.getAttribute('id');
                }
            });

            navLinksAll.forEach(link => {
                const href = link.getAttribute('href');
                if (href && href.startsWith('#')) {
                    link.classList.remove('text-amber-400');
                    link.classList.add('text-ink-300');
                    
                    if (href === '#' + current) {
                        link.classList.remove('text-ink-300');
                        link.classList.add('text-amber-400');
                    }
                }
            });
        }

        // Initialiser et écouter le scroll
        updateActiveLink();
        window.addEventListener('scroll', updateActiveLink);
        window.addEventListener('resize', function() {
            navbarHeight = navbar ? navbar.offsetHeight : 64;
        });
    });
</script>