{{-- resources/views/public/contact.blade.php
     Direction "Blueprint" — formulaire de contact. Poste vers contact.send.
     Honeypot "website" (caché) + validation côté serveur (ContactRequest). --}}
@extends('layouts.app')

@section('title', 'Contact — AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">Contact</span>
@endsection

@section('content')
<div class="max-w-4xl">

    {{-- En-tête --}}
    <div class="relative bg-ink-900 border border-ink-700 p-6 mb-6">
        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>
        <h1 class="font-display text-2xl font-bold text-fg">Nous contacter</h1>
        <p class="font-mono text-[12px] text-ink-400 mt-1">
            Une question sur une référence, un ouvrage ou une demande de prix ? Écrivez-nous.
        </p>
    </div>

    {{-- Messages flash --}}
    @if(session('success'))
        <div role="status" class="mb-6 border border-green-500/40 bg-green-500/10 text-green-400 px-4 py-3 font-mono text-[12px]">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div role="alert" class="mb-6 border border-red-500/40 bg-red-500/10 text-red-400 px-4 py-3 font-mono text-[12px]">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Formulaire --}}
        <form method="POST" action="{{ route('contact.send') }}" novalidate
              class="lg:col-span-2 bg-ink-900 border border-ink-800">
            @csrf

            <div class="px-4 py-2.5 bg-ink-950 border-b border-ink-800">
                <h2 class="font-mono text-[10px] font-bold text-ink-500 uppercase tracking-widest">Formulaire</h2>
            </div>

            <div class="p-4 space-y-4">

                {{-- Honeypot : invisible pour les humains --}}
                <div class="hidden" aria-hidden="true">
                    <label for="website">Ne pas remplir</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Nom --}}
                    <div>
                        <label for="nom" class="block font-mono text-[10px] text-ink-400 uppercase tracking-widest mb-1">
                            Nom <span class="text-amber-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required maxlength="120"
                               class="w-full bg-ink-950 border @error('nom') border-red-500/70 @else border-ink-700 @enderror
                                      px-3 py-2 text-sm text-ink-50 placeholder-ink-600 outline-none focus:border-amber-500 transition">
                        @error('nom') <p class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p> @enderror
                    </div>

                    {{-- Courriel --}}
                    <div>
                        <label for="email" class="block font-mono text-[10px] text-ink-400 uppercase tracking-widest mb-1">
                            Courriel <span class="text-amber-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required maxlength="190"
                               class="w-full bg-ink-950 border @error('email') border-red-500/70 @else border-ink-700 @enderror
                                      px-3 py-2 text-sm text-ink-50 placeholder-ink-600 outline-none focus:border-amber-500 transition">
                        @error('email') <p class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Entreprise --}}
                <div>
                    <label for="entreprise" class="block font-mono text-[10px] text-ink-400 uppercase tracking-widest mb-1">
                        Entreprise <span class="text-ink-600">(optionnel)</span>
                    </label>
                    <input type="text" id="entreprise" name="entreprise" value="{{ old('entreprise') }}" maxlength="150"
                           class="w-full bg-ink-950 border @error('entreprise') border-red-500/70 @else border-ink-700 @enderror
                                  px-3 py-2 text-sm text-ink-50 outline-none focus:border-amber-500 transition">
                    @error('entreprise') <p class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Sujet --}}
                <div>
                    <label for="sujet" class="block font-mono text-[10px] text-ink-400 uppercase tracking-widest mb-1">
                        Sujet <span class="text-amber-500">*</span>
                    </label>
                    <input type="text" id="sujet" name="sujet" value="{{ old('sujet', request('sujet')) }}" required maxlength="190"
                           class="w-full bg-ink-950 border @error('sujet') border-red-500/70 @else border-ink-700 @enderror
                                  px-3 py-2 text-sm text-ink-50 outline-none focus:border-amber-500 transition">
                    @error('sujet') <p class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Message --}}
                <div>
                    <label for="message" class="block font-mono text-[10px] text-ink-400 uppercase tracking-widest mb-1">
                        Message <span class="text-amber-500">*</span>
                    </label>
                    <textarea id="message" name="message" rows="7" required maxlength="5000"
                              class="w-full bg-ink-950 border @error('message') border-red-500/70 @else border-ink-700 @enderror
                                     px-3 py-2 text-sm text-ink-50 outline-none focus:border-amber-500 transition resize-y">{{ old('message') }}</textarea>
                    @error('message') <p class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between gap-4 pt-1">
                    <p class="font-mono text-[10px] text-ink-500">* champs obligatoires</p>
                    <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 bg-amber-500/10 text-amber-400 border border-amber-500/40
                                   hover:bg-amber-500/20 transition font-mono text-xs font-semibold uppercase tracking-wider">
                        Envoyer le message
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </button>
                </div>
            </div>
        </form>

        {{-- Encart info --}}
        <aside class="bg-ink-900 border border-ink-800 self-start">
            <div class="px-4 py-2.5 bg-ink-950 border-b border-ink-800">
                <h2 class="font-mono text-[10px] font-bold text-ink-500 uppercase tracking-widest">Informations</h2>
            </div>
            <ul class="p-4 space-y-3 text-sm text-ink-400">
                <li>
                    <span class="block font-mono text-[10px] text-ink-500 uppercase tracking-widest mb-0.5">Réponse</span>
                    Nous répondons généralement sous 1 à 2 jours ouvrables.
                </li>
                <li>
                    <span class="block font-mono text-[10px] text-ink-500 uppercase tracking-widest mb-0.5">Accusé de réception</span>
                    Une copie de votre message vous est envoyée par courriel.
                </li>
                <li>
                    <span class="block font-mono text-[10px] text-ink-500 uppercase tracking-widest mb-0.5">Une référence précise ?</span>
                    Indiquez le code (ex. ALN-…) dans le sujet pour accélérer la réponse.
                </li>
            </ul>
        </aside>
    </div>
</div>
@endsection