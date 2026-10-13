{{-- resources/views/admin/categories/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Nouvelle catégorie - Administration')

@section('content')
<div class="max-w-5xl">
    <div class="mb-4">
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-admin-400 hover:text-admin-600">← Catégories</a>
        <h1 class="text-lg font-semibold text-admin-900 mt-1">Nouvelle catégorie</h1>
        <p class="text-xs text-admin-500 mt-0.5">Créer une catégorie pour organiser vos ouvrages</p>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-4">
                <section class="bg-white rounded border border-admin-200">
                    <div class="px-4 py-3 border-b border-admin-200">
                        <h2 class="text-sm font-semibold text-admin-700">Informations</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div>
                            <label for="nom" class="block text-xs font-medium text-admin-600 mb-1">Nom *</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
                                   class="w-full px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                            @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="description" class="block text-xs font-medium text-admin-600 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4"
                                      class="w-full px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>
            </div>

            {{-- Réglages --}}
            <aside class="space-y-4">
                <section class="bg-white rounded border border-admin-200">
                    <div class="px-4 py-3 border-b border-admin-200">
                        <h2 class="text-sm font-semibold text-admin-700">Réglages</h2>
                    </div>
                    <div class="p-4 space-y-4">
                        <div>
                            <label for="icone" class="block text-xs font-medium text-admin-600 mb-1">Icône</label>
                            <input type="text" id="icone" name="icone" value="{{ old('icone', 'fa-tag') }}" placeholder="fa-tag"
                                   class="w-full px-3 py-1.5 text-sm font-mono border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                            <p class="text-xs text-admin-400 mt-1">Classe Font Awesome (ex : fa-tag, fa-star)</p>
                            @error('icone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="ordre" class="block text-xs font-medium text-admin-600 mb-1">Ordre d'affichage</label>
                            <input type="number" id="ordre" name="ordre" value="{{ old('ordre', 0) }}"
                                   class="w-full px-3 py-1.5 text-sm border border-admin-200 rounded focus:outline-none focus:ring-1 focus:ring-admin-400">
                            @error('ordre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-admin-300 text-amber-500 focus:ring-amber-500">
                            <span class="text-sm text-admin-700">Actif</span>
                        </label>
                    </div>
                </section>
            </aside>
        </div>

        {{-- Boutons --}}
        <div class="mt-4 flex items-center justify-end gap-2">
            <a href="{{ route('admin.categories.index') }}"
               class="px-3 py-1.5 bg-white hover:bg-admin-50 text-admin-600 border border-admin-200 text-sm rounded transition">
                Annuler
            </a>
            <button type="submit" class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                Créer la catégorie
            </button>
        </div>
    </form>
</div>
@endsection