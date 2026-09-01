{{-- resources/views/admin/partials/topbar.blade.php --}}

<!-- Top Navigation Bar -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="flex items-center justify-between px-6 py-3">
        <div class="flex items-center space-x-4">
            <!-- Bouton toggle sidebar (mobile) -->
            <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none" @click="sidebarOpen = !sidebarOpen">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Titre de la page -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    @yield('page-title', 'Administration')
                </h2>
                <p class="text-sm text-gray-500 hidden sm:block">
                    @yield('page-subtitle', 'Gérez votre site vitrine AluStock')
                </p>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Notification -->
            <button class="relative p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-bell text-lg"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
            
            <!-- User Info -->
            <div class="flex items-center space-x-3 pl-4 border-l border-gray-200">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-bold shadow-md">
                    {{ Auth::user() ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AD' }}
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user() ? Auth::user()->name : 'Admin' }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user() ? Auth::user()->email : 'admin@alustock.fr' }}</p>
                </div>
            </div>
        </div>
    </div>
</header>