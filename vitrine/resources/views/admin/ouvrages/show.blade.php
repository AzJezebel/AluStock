{{-- resources/views/admin/ouvrages/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', $ouvrage->titre . ' - Administration')

@section('content')
@php
    // Image principale d'abord, puis la galerie (sans doublon)
    $images = collect([$ouvrage->mainImage])->filter()->concat($ouvrage->gallery)->unique('id')->values();
    $mainId = $ouvrage->mainImage?->id;
@endphp
<div>
    {{-- En-tête --}}
    <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
        <div class="min-w-0">
            <a href="{{ route('admin.ouvrages.index') }}" class="text-xs text-admin-400 hover:text-admin-600">← Ouvrages</a>
            <h1 class="text-lg font-semibold text-admin-900 mt-1">{{ $ouvrage->titre }}</h1>
            <div class="flex flex-wrap items-center gap-2 mt-1">
                <span class="font-mono text-xs text-admin-500 bg-admin-100 px-2 py-0.5">{{ $ouvrage->reference }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $ouvrage->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-admin-100 text-admin-500' }}">
                    {{ $ouvrage->is_active ? 'Actif' : 'Inactif' }}
                </span>
                @if($ouvrage->is_featured)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-700">En vedette</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}"
               class="px-3 py-1.5 bg-admin-900 hover:bg-admin-800 text-white text-sm rounded transition">
                Modifier
            </a>
            <form action="{{ route('admin.ouvrages.destroy', $ouvrage) }}" method="POST"
                  onsubmit="return confirm('Supprimer cet ouvrage et ses images ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 bg-white hover:bg-red-50 text-red-500 border border-admin-200 text-sm rounded transition">
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Description --}}
            <section class="bg-white rounded border border-admin-200">
                <div class="px-4 py-3 border-b border-admin-200">
                    <h2 class="text-sm font-semibold text-admin-700">Description</h2>
                </div>
                <div class="p-4 text-sm text-admin-700 whitespace-pre-line">
                    @if($ouvrage->description)
                        {{ $ouvrage->description }}
                    @else
                        <span class="text-admin-400">Aucune description.</span>
                    @endif
                </div>
            </section>

            {{-- Spécifications --}}
            @if($ouvrage->specifications)
                <section class="bg-white rounded border border-admin-200">
                    <div class="px-4 py-3 border-b border-admin-200">
                        <h2 class="text-sm font-semibold text-admin-700">Spécifications</h2>
                    </div>
                    <div class="p-4 text-sm text-admin-700 whitespace-pre-line">{{ $ouvrage->specifications }}</div>
                </section>
            @endif

            {{-- Images --}}
            <section class="bg-white rounded border border-admin-200">
                <div class="px-4 py-3 border-b border-admin-200 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-admin-700">
                        Images
                        <span class="text-xs text-admin-400 font-normal ml-1">({{ $images->count() }})</span>
                    </h2>
                    <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" class="text-xs text-admin-400 hover:text-admin-600">Gérer les images</a>
                </div>

                @if($images->isNotEmpty())
                    <div class="p-4 grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($images as $image)
                            <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank"
                               class="relative group block bg-admin-50 rounded border border-admin-200 overflow-hidden">
                                @if($image->id === $mainId)
                                    <span class="absolute top-1 left-1 z-10 bg-amber-500 text-white text-[9px] font-semibold px-1.5 py-0.5 rounded">PRINCIPALE</span>
                                @endif
                                <div class="aspect-video bg-white overflow-hidden">
                                    <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->file_name }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-sm text-admin-400">
                        Aucune image pour l'instant.
                        <a href="{{ route('admin.ouvrages.edit', $ouvrage) }}" class="text-admin-600 hover:text-admin-800 underline ml-1">En ajouter</a>
                    </div>
                @endif
            </section>
        </div>

        {{-- Informations --}}
        <aside>
            <section class="bg-white rounded border border-admin-200">
                <div class="px-4 py-3 border-b border-admin-200">
                    <h2 class="text-sm font-semibold text-admin-700">Informations</h2>
                </div>
                <dl class="divide-y divide-admin-100 text-sm">
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Catégorie</dt>
                        <dd class="text-admin-900 text-right">{{ $ouvrage->categorie->nom ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Réalisation</dt>
                        <dd class="text-admin-900 text-right">{{ $ouvrage->date_realisation?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Client</dt>
                        <dd class="text-admin-900 text-right">{{ $ouvrage->client ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Localisation</dt>
                        <dd class="text-admin-900 text-right">{{ $ouvrage->localisation ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Vues</dt>
                        <dd class="text-admin-900 text-right">{{ number_format($ouvrage->views ?? 0, 0, ',', ' ') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Créé le</dt>
                        <dd class="text-admin-900 text-right">{{ $ouvrage->created_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 px-4 py-2.5">
                        <dt class="text-admin-500">Modifié le</dt>
                        <dd class="text-admin-900 text-right">{{ $ouvrage->updated_at->format('d/m/Y à H:i') }}</dd>
                    </div>
                </dl>
            </section>
        </aside>
    </div>
</div>
@endsection