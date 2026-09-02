{{-- resources/views/admin/ouvrages/show.blade.php --}}

@extends('admin.layouts.admin')

@section('title', $ouvrage->titre . ' - AluStock Admin')
@section('page-title', $ouvrage->titre)
@section('page-subtitle', 'Détails de l\'ouvrage')

@section('content')
<div class="space-y-6">
    <!-- Informations -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $ouvrage->titre }}</h2>
                <p class="text-gray-500">Référence: {{ $ouvrage->reference }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Modifier
                </a>
                <a href="{{ route('admin.ouvrages.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Catégorie</h4>
                    <p class="text-gray-800">{{ $ouvrage->categorie->nom ?? 'Non catégorisé' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Gamme</h4>
                    <p class="text-gray-800">{{ $ouvrage->gamme->nom ?? 'Non assigné' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Date de réalisation</h4>
                    <p class="text-gray-800">{{ $ouvrage->date_realisation?->format('d/m/Y') ?? 'Non spécifiée' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Client</h4>
                    <p class="text-gray-800">{{ $ouvrage->client ?? 'Non spécifié' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Localisation</h4>
                    <p class="text-gray-800">{{ $ouvrage->localisation ?? 'Non spécifiée' }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Statut</h4>
                    <span class="px-3 py-1 inline-block text-sm rounded-full {{ $ouvrage->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $ouvrage->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Vedette</h4>
                    <span class="text-amber-500">
                        <i class="fas {{ $ouvrage->is_featured ? 'fa-star' : 'fa-star-o' }}"></i>
                        {{ $ouvrage->is_featured ? 'En vedette' : 'Non vedette' }}
                    </span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Vues</h4>
                    <p class="text-gray-800">{{ number_format($ouvrage->views) }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Créé le</h4>
                    <p class="text-gray-800">{{ $ouvrage->created_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Dernière modification</h4>
                    <p class="text-gray-800">{{ $ouvrage->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($ouvrage->description)
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Description</h4>
            <p class="text-gray-800">{{ $ouvrage->description }}</p>
        </div>
        @endif

        <!-- Spécifications -->
        @if($ouvrage->specifications)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Spécifications</h4>
            <p class="text-gray-800">{{ $ouvrage->specifications }}</p>
        </div>
        @endif
    </div>

    <!-- Galerie -->
    @if($ouvrage->medias && $ouvrage->medias->count() > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Galerie</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($ouvrage->medias as $media)
            <div class="relative group">
                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->file_name }}" class="w-full h-48 object-cover rounded-lg">
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                    <a href="{{ asset('storage/' . $media->file_path) }}" target="_blank" class="text-white p-2 hover:text-amber-500 transition-colors">
                        <i class="fas fa-expand text-xl"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection