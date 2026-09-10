{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AluStock Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        admin: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                            300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b',
                            600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-admin-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-amber-500 rounded-lg mb-3">
                <span class="text-admin-900 font-bold text-lg">A</span>
            </div>
            <h1 class="text-xl font-semibold text-white">AluStock Admin</h1>
            <p class="text-admin-400 text-sm mt-1">Accès réservé</p>
        </div>

        {{-- Messages --}}
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-900/30 border border-red-700/50 text-red-300 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-900/30 border border-green-700/50 text-green-300 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Formulaire --}}
        <form action="{{ route('admin.login.post') }}" method="POST" 
              class="bg-white rounded-lg shadow-xl p-6 space-y-4">
            @csrf

            <div>
                <label for="password" class="block text-xs font-medium text-admin-600 mb-1.5">
                    Mot de passe administrateur
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required 
                       autofocus
                       class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                       placeholder="••••••••">
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm font-medium rounded transition">
                Se connecter
            </button>
        </form>

        {{-- Retour au site --}}
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-admin-400 hover:text-admin-300 transition">
                ← Retour au site public
            </a>
        </div>
    </div>

</body>
</html>