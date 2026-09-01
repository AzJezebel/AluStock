{{-- resources/views/admin/dashboard/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Dashboard - AluStock Admin')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de votre site AluStock')

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Ouvrages -->
        <div class="card bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Ouvrages</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_ouvrages'] ?? 0 }}</p>
                    <p class="text-xs text-emerald-600 mt-1">
                        <i class="fas fa-check-circle"></i> {{ $stats['active_ouvrages'] ?? 0 }} actifs
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-images text-blue-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.ouvrages.index') }}" class="mt-4 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                Gérer les ouvrages <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <!-- Catégories -->
        <div class="card bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Catégories</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_categories'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-emerald-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="mt-4 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                Gérer les catégories <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <!-- Gammes -->
        <div class="card bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Gammes</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_gammes'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cubes text-purple-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.gammes.index') }}" class="mt-4 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                Gérer les gammes <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <!-- Médias -->
        <div class="card bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Médias</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_medias'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-photo-video text-amber-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.medias.index') }}" class="mt-4 inline-block text-sm text-amber-600 hover:text-amber-700 font-medium">
                Gérer les médias <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Derniers ouvrages -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Derniers ouvrages ajoutés</h3>
            <a href="{{ route('admin.ouvrages.index') }}" class="text-sm text-amber-600 hover:text-amber-700">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Titre</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Référence</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Catégorie</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Date</th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestOuvrages ?? [] as $ouvrage)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.ouvrages.show', $ouvrage) }}" class="text-gray-800 hover:text-amber-600">
                                {{ $ouvrage->titre }}
                            </a>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $ouvrage->reference }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $ouvrage->categorie->nom ?? '-' }}</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $ouvrage->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $ouvrage->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $ouvrage->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-500">Aucun ouvrage pour le moment</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Actions rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('admin.ouvrages.create') }}" class="card bg-gradient-to-r from-amber-500 to-amber-600 rounded-lg shadow-sm p-6 text-white hover:shadow-md">
            <div class="flex items-center space-x-4">
                <i class="fas fa-plus-circle text-3xl"></i>
                <div>
                    <h4 class="font-semibold">Ajouter un ouvrage</h4>
                    <p class="text-sm text-amber-100">Nouvelle réalisation</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.categories.create') }}" class="card bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white hover:shadow-md">
            <div class="flex items-center space-x-4">
                <i class="fas fa-tag text-3xl"></i>
                <div>
                    <h4 class="font-semibold">Nouvelle catégorie</h4>
                    <p class="text-sm text-blue-100">Organiser les ouvrages</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('vitrine.index') }}" target="_blank" class="card bg-gradient-to-r from-gray-700 to-gray-800 rounded-lg shadow-sm p-6 text-white hover:shadow-md">
            <div class="flex items-center space-x-4">
                <i class="fas fa-eye text-3xl"></i>
                <div>
                    <h4 class="font-semibold">Voir le site</h4>
                    <p class="text-sm text-gray-300">Aperçu public</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection