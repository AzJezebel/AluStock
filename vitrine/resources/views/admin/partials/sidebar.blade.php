{{-- resources/views/admin/partials/sidebar.blade.php --}}

<!-- Sidebar -->
<aside class="sidebar bg-[#0f1113] text-white w-64 flex-shrink-0 overflow-y-auto h-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center space-x-3 mb-8">
            <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-cube text-ink-950 text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold">Alu<span class="text-amber-500">Stock</span></h1>
                <p class="text-xs text-gray-400">Administration</p>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Ouvrages -->
            <a href="{{ route('admin.ouvrages.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white {{ request()->routeIs('admin.ouvrages.*') ? 'active' : '' }}">
                <i class="fas fa-images w-5"></i>
                <span>Ouvrages</span>
                <span class="ml-auto bg-amber-500/20 text-amber-400 text-xs px-2 py-1 rounded-full">{{ \App\Models\Ouvrage::count() }}</span>
            </a>
            
            <!-- Catégories -->
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags w-5"></i>
                <span>Catégories</span>
                <span class="ml-auto bg-amber-500/20 text-amber-400 text-xs px-2 py-1 rounded-full">{{ \App\Models\Categorie::count() }}</span>
            </a>
            
            <!-- Gammes -->
            <a href="{{ route('admin.gammes.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white {{ request()->routeIs('admin.gammes.*') ? 'active' : '' }}">
                <i class="fas fa-cubes w-5"></i>
                <span>Gammes</span>
                <span class="ml-auto bg-amber-500/20 text-amber-400 text-xs px-2 py-1 rounded-full">{{ \App\Models\Gamme::count() }}</span>
            </a>
            
            <!-- Médias -->
            <a href="{{ route('admin.medias.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white {{ request()->routeIs('admin.medias.*') ? 'active' : '' }}">
                <i class="fas fa-photo-video w-5"></i>
                <span>Médias</span>
                <span class="ml-auto bg-amber-500/20 text-amber-400 text-xs px-2 py-1 rounded-full">{{ \App\Models\Media::count() }}</span>
            </a>
            
            <!-- Paramètres -->
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog w-5"></i>
                <span>Paramètres</span>
            </a>
            
            <hr class="border-gray-700 my-4">
            
            <!-- Voir le site -->
            <a href="{{ route('vitrine.index') }}" target="_blank" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white">
                <i class="fas fa-external-link-alt w-5"></i>
                <span>Voir le site</span>
            </a>
            
            <!-- Déconnexion -->
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="sidebar-link w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:text-red-400 text-left">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </nav>
    </div>
</aside>

<!-- Mobile overlay -->
<div x-show="!sidebarOpen" @click="sidebarOpen = true" class="fixed inset-0 bg-black/50 z-40 lg:hidden" style="display: none;"></div>