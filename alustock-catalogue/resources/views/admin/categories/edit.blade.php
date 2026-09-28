{{-- resources/views/admin/categories/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Éditer - ' . $categorie->nom)

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">{{ $categorie->nom }}</h1>
            <p class="text-xs text-admin-500 mt-0.5">
                Slug : <span class="font-mono">{{ $categorie->slug }}</span>
                @if($categorie->ouvrages_count > 0)
                    — {{ $categorie->ouvrages_count }} ouvrage(s) lié(s)
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('ouvrages.index', ['categorie' => $categorie->slug]) }}" 
               target="_blank"
               class="text-xs text-admin-500 hover:text-admin-700">Voir sur le site ↗</a>
            <a href="{{ route('admin.categories.index') }}" 
               class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
        </div>
    </div>

    {{-- Formulaire --}}
    <form action="{{ route('admin.categories.update', $categorie) }}" method="POST" 
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-2xl">
        @csrf
        @method('PUT')

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
                           value="{{ old('nom', $categorie->nom) }}"
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
                           value="{{ old('icone', $categorie->icone) }}"
                           placeholder="🪟"
                           maxlength="10"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('icone')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-medium text-admin-600 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description', $categorie->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-admin-100">
            <button type="button" 
                    onclick="if(confirm('Supprimer cette catégorie ? Les ouvrages liés perdront leur catégorie.')) document.getElementById('delete-categorie-form').submit();"
                    class="text-xs text-red-400 hover:text-red-600 transition">
                Supprimer cette catégorie
            </button>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.categories.index') }}" 
                   class="px-4 py-2 text-sm text-admin-500 hover:text-admin-700 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                    Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>

    {{-- Formulaire suppression (hors du principal) --}}
    <form id="delete-categorie-form" action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection