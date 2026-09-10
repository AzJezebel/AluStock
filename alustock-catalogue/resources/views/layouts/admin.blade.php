{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration - AluStock')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        admin: {
                            50:  '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        html, body { height: 100%; }
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .admin-content { flex: 1 0 auto; }
        .admin-footer { flex-shrink: 0; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Drag & drop */
        .sortable-ghost { opacity: 0.4; background: #f1f5f9; }
        .sortable-chosen { background: #e2e8f0; }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-admin-50 text-admin-800">

    {{-- ============================================================
         HEADER ADMIN
         ============================================================ --}}
    <header class="bg-admin-900 text-white flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                
                {{-- Logo --}}
                <div class="flex items-center space-x-6">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-7 h-7 bg-amber-500 rounded flex items-center justify-center text-admin-900 font-bold text-sm">
                            A
                        </div>
                        <span class="text-sm font-semibold tracking-tight">AluStock Admin</span>
                    </a>

                    {{-- Navigation principale --}}
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-3 py-1.5 text-sm rounded transition {{ request()->routeIs('admin.dashboard') ? 'bg-admin-800 text-white' : 'text-admin-300 hover:text-white hover:bg-admin-800' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.ouvrages.index') }}" 
                           class="px-3 py-1.5 text-sm rounded transition {{ request()->routeIs('admin.ouvrages.*') ? 'bg-admin-800 text-white' : 'text-admin-300 hover:text-white hover:bg-admin-800' }}">
                            Ouvrages
                        </a>
                        <a href="{{ route('admin.composants.index') }}" 
                           class="px-3 py-1.5 text-sm rounded transition {{ request()->routeIs('admin.composants.*') ? 'bg-admin-800 text-white' : 'text-admin-300 hover:text-white hover:bg-admin-800' }}">
                            Composants
                        </a>
                        <a href="{{ route('admin.gammes.index') }}" 
                           class="px-3 py-1.5 text-sm rounded transition {{ request()->routeIs('admin.gammes.*') ? 'bg-admin-800 text-white' : 'text-admin-300 hover:text-white hover:bg-admin-800' }}">
                            Gammes
                        </a>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="px-3 py-1.5 text-sm rounded transition {{ request()->routeIs('admin.categories.*') ? 'bg-admin-800 text-white' : 'text-admin-300 hover:text-white hover:bg-admin-800' }}">
                            Catégories
                        </a>
                    </nav>
                </div>

                {{-- Actions droite --}}
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" target="_blank" 
                       class="text-xs text-admin-400 hover:text-white transition">
                        Voir le site ↗
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-1.5 text-xs bg-admin-800 hover:bg-admin-700 text-admin-300 hover:text-white rounded transition">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div class="md:hidden border-t border-admin-800">
            <div class="px-4 py-2 flex items-center space-x-2 overflow-x-auto">
                <a href="{{ route('admin.dashboard') }}" class="whitespace-nowrap px-3 py-1.5 text-xs rounded {{ request()->routeIs('admin.dashboard') ? 'bg-admin-800 text-white' : 'text-admin-300' }}">Dashboard</a>
                <a href="{{ route('admin.ouvrages.index') }}" class="whitespace-nowrap px-3 py-1.5 text-xs rounded {{ request()->routeIs('admin.ouvrages.*') ? 'bg-admin-800 text-white' : 'text-admin-300' }}">Ouvrages</a>
                <a href="{{ route('admin.composants.index') }}" class="whitespace-nowrap px-3 py-1.5 text-xs rounded {{ request()->routeIs('admin.composants.*') ? 'bg-admin-800 text-white' : 'text-admin-300' }}">Composants</a>
                <a href="{{ route('admin.gammes.index') }}" class="whitespace-nowrap px-3 py-1.5 text-xs rounded {{ request()->routeIs('admin.gammes.*') ? 'bg-admin-800 text-white' : 'text-admin-300' }}">Gammes</a>
                <a href="{{ route('admin.categories.index') }}" class="whitespace-nowrap px-3 py-1.5 text-xs rounded {{ request()->routeIs('admin.categories.*') ? 'bg-admin-800 text-white' : 'text-admin-300' }}">Catégories</a>
            </div>
        </div>
    </header>

    {{-- ============================================================
         CONTENU
         ============================================================ --}}
    <main class="admin-content py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Messages flash --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded text-sm flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">✕</button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded text-sm flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">✕</button>
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded text-sm flex items-center justify-between">
                    <span>{{ session('warning') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700">✕</button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- ============================================================
         FOOTER
         ============================================================ --}}
    <footer class="admin-footer bg-white border-t border-admin-200 py-3 flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-admin-400">
            &copy; {{ date('Y') }} AluStock — Administration
        </div>
    </footer>

    @stack('scripts')
</body>
</html>