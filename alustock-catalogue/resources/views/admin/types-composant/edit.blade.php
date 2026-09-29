{{-- resources/views/admin/types-composant/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Éditer - ' . $typeComposant->nom)

@section('content')
<div>
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">{{ $typeComposant->nom }}</h1>
            <p class="text-xs text-admin-500 mt-0.5">
                Slug : <span class="font-mono">{{ $typeComposant->slug }}</span>
                @if($typeComposant->composants_count > 0)
                    — {{ $typeComposant->composants_count }} composant(s) lié(s)
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.types-composant.index') }}" 
               class="text-xs text-admin-500 hover:text-admin-700">← Retour</a>
        </div>
    </div>

    <form action="{{ route('admin.types-composant.update', $typeComposant) }}" method="POST" 
          class="bg-white rounded border border-admin-200 p-6 space-y-5 max-w-2xl">
        @csrf
        @method('PUT')

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
                           value="{{ old('nom', $typeComposant->nom) }}"
                           class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">
                    @error('nom')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-xs font-medium text-admin-600 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-3 py-2 text-sm border border-admin-200 rounded focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('description', $typeComposant->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-admin-100">
            <button type="button" 
                    onclick="if(confirm('Supprimer ce type ? Les composants liés perdront leur type.')) document.getElementById('delete-type-form').submit();"
                    class="text-xs text-red-400 hover:text-red-600 transition">
                Supprimer ce type
            </button>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.types-composant.index') }}" 
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

    <form id="delete-type-form" action="{{ route('admin.types-composant.destroy', $typeComposant) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection