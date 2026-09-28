{{-- resources/views/admin/categories/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Nouvelle catégorie - Administration')

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">Nouvelle catégorie</h1>
            <p class="text-xs text-admin-500 mt-0.5">Créer une nouvelle catégorie d'ouvrages</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" 
           class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
    </div>

    {{-- Formulaire --}}
    <form action="{{ route('admin.categories.store') }}" method="POST" 
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-2xl">
        @csrf

        {{-- Identification --}}
        <div>
            <h2 class="text-xs font-semibold text-admin-400 uppercase tracking-wider mb-3 pb-2 border-b border-admin-100">
                Identification
            </h2>
            <div class="space-y-4">
                <div>
                    <label for="nom" class="block text-xs font-medium text-admin-600 mb-1">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nom" id="nom" required
                           value="{{ old('nom') }}"
                           placeholder="Fenêtre"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('nom')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="icone" class="block text-xs font-medium text-admin-600 mb-1">
                        Icône (emoji)
                    </label>
                    <input type="text" name="icone" id="icone"
                           value="{{ old('icone') }}"
                           placeholder="🪟"
                           maxlength="10"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <p class="mt-1 text-xs text-admin-400">Un emoji simple (ex: 🪟 🚪 🏠)</p>
                    @error('icone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-medium text-admin-600 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Description de la catégorie"
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-2 pt-4 border-t border-admin-100">
            <a href="{{ route('admin.categories.index') }}" 
               class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                Annuler
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                Créer la catégorie
            </button>
        </div>
    </form>
</div>
@endsection