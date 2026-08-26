{{-- resources/views/public/categories/show.blade.php --}}
@extends('layouts.app')

@section('title', $categorie->nom . ' - AluStock')

@section('page-header')
    <div class="mb-6">
        <nav class="flex items-center text-sm text-aluminum-500 mb-2">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Accueil</a>
            <span class="mx-2">›</span>
            <a href="{{ route('categories.index') }}" class="hover:text-primary-600">Catégories</a>
            <span class="mx-2">›</span>
            <span class="text-aluminum-700 font-medium">{{ $categorie->nom }}</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-aluminum-900 flex items-center gap-3">
                    @if($categorie->icone)
                        <span>{{ $categorie->icone }}</span>
                    @endif
                    {{ $categorie->nom }}
                </h1>
                @if($categorie->description)
                    <p class="text-aluminum-500 text-sm mt-1">{{ $categorie->description }}</p>
                @endif
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-4 text-sm">
                <span class="text-aluminum-400">
                    <span class="font-medium text-aluminum-700">{{ $ouvrages->total() }}</span> ouvrage(s)
                </span>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div>
    {{-- Grille des ouvrages de la catégorie --}}
    @if($ouvrages->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ouvrages as $ouvrage)
                <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-aluminum-200 hover:border-primary-200">
                    <div class="p-5">
                        <h3 class="text-base font-semibold text-aluminum-900 group-hover:text-primary-600 transition">
                            <a href="{{ route('ouvrages.show', $ouvrage->slug) }}">
                                {{ $ouvrage->nom }}
                            </a>
                        </h3>
                        <p class="text-sm text-aluminum-500 mt-1 line-clamp-2">
                            {{ $ouvrage->description_courte ?? Str::limit($ouvrage->description_technique ?? '', 80) }}
                        </p>
                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-aluminum-100">
                            <span class="text-xs text-aluminum-400">
                                {{ $ouvrage->gamme?->nom ?? 'Sans gamme' }}
                            </span>
                            <a href="{{ route('ouvrages.show', $ouvrage->slug) }}" 
                               class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-800 transition">
                                Voir
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $ouvrages->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-xl border border-aluminum-200">
            <p class="text-aluminum-500">Aucun ouvrage disponible dans cette catégorie.</p>
        </div>
    @endif
</div>
@endsection