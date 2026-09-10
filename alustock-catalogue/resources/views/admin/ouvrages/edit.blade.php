{{-- resources/views/admin/ouvrages/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Éditer - ' . $ouvrage->nom)

@section('content')
<div>
    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-lg font-semibold text-admin-900">{{ $ouvrage->nom }}</h1>
            <p class="text-xs text-admin-500 mt-0.5">
                Réf. <span class="font-mono">{{ $ouvrage->reference }}</span>
                @if($ouvrage->gamme) — {{ $ouvrage->gamme->nom }} @endif
                @if($ouvrage->categorie) — {{ $ouvrage->categorie->nom }} @endif
            </p>
        </div>
        <a href="{{ route('admin.ouvrages.index') }}" 
           class="text-xs text-admin-500 hover:text-admin-700">← Retour à la liste</a>
    </div>

    {{-- Layout en 2 colonnes --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Section : Informations générales --}}
            @include('admin.ouvrages.partials.infos', [
                'ouvrage' => $ouvrage, 
                'gammes' => $gammes, 
                'categories' => $categories
            ])

            {{-- Section : Composition --}}
            @include('admin.ouvrages.partials.composition', [
                'ouvrage' => $ouvrage, 
                'composantsDisponibles' => $composantsDisponibles, 
                'typesComposant' => $typesComposant
            ])

            {{-- Section : Caractéristiques EAV --}}
            @include('admin.ouvrages.partials.caracteristiques', ['ouvrage' => $ouvrage])

        </div>

        {{-- Colonne latérale --}}
        <div class="space-y-6">
            
            {{-- Section : Médias --}}
            @include('admin.ouvrages.partials.medias', ['ouvrage' => $ouvrage])

        </div>
    </div>
</div>
@endsection