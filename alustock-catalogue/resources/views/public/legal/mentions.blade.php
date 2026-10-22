{{-- resources/views/public/legal/mentions.blade.php
     Mentions légales (LCEN). Les valeurs viennent de config/legal.php. --}}
@extends('layouts.app')

@section('title', 'Mentions légales — AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">Mentions légales</span>
@endsection

@section('content')
<div class="max-w-4xl">

    <div class="relative bg-ink-900 border border-ink-700 p-6 mb-6">
        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>
        <h1 class="font-display text-2xl font-bold text-fg">Mentions légales</h1>
        <p class="font-mono text-[12px] text-ink-400 mt-1">Dernière mise à jour : {{ $l['mise_a_jour'] }}</p>
    </div>

    <div class="space-y-6 text-sm text-ink-300 leading-relaxed">

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Éditeur du site</h2>
            <p>
                <strong class="text-fg">{{ $l['editeur']['raison_sociale'] }}</strong>,
                {{ $l['editeur']['forme_juridique'] }} au capital de {{ $l['editeur']['capital'] }}<br>
                Siège social : {{ $l['editeur']['adresse'] }}<br>
                Immatriculation : {{ $l['editeur']['siren'] }}<br>
                TVA intracommunautaire : {{ $l['editeur']['tva'] }}<br>
                Courriel : <a href="mailto:{{ $l['editeur']['email'] }}" class="text-amber-400 hover:underline">{{ $l['editeur']['email'] }}</a><br>
                Téléphone : {{ $l['editeur']['telephone'] }}
            </p>
            <p class="mt-3">Directeur de la publication : {{ $l['editeur']['directeur_publication'] }}</p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Hébergeur</h2>
            <p>
                {{ $l['hebergeur']['nom'] }}<br>
                {{ $l['hebergeur']['adresse'] }}<br>
                Téléphone : {{ $l['hebergeur']['telephone'] }}
            </p>
        </section>

        @if(!empty($l['realisation']))
        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Réalisation</h2>
            <p>{{ $l['realisation'] }}</p>
        </section>
        @endif

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Propriété intellectuelle</h2>
            <p>
                L'ensemble du contenu de ce site (textes, schémas, images, références, structure) est protégé par le droit
                d'auteur. Toute reproduction ou réutilisation, totale ou partielle, sans autorisation écrite préalable
                de l'éditeur est interdite. Les marques, noms de profilés et références de fabricants cités appartiennent
                à leurs titulaires respectifs.
            </p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Responsabilité</h2>
            <p>
                Les informations du catalogue (références, dimensions, caractéristiques) sont fournies à titre indicatif
                et peuvent être modifiées sans préavis. Elles ne constituent pas une offre contractuelle : pour toute
                demande de prix ou de confirmation technique, <a href="{{ route('contact.show') }}" class="text-amber-400 hover:underline">contactez-nous</a>.
                L'éditeur ne saurait être tenu responsable d'un usage inapproprié de ces informations.
            </p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Données personnelles et cookies</h2>
            <p>
                Voir la <a href="{{ route('legal.confidentialite') }}" class="text-amber-400 hover:underline">politique de confidentialité</a>.
                Ce site n'utilise que des cookies strictement nécessaires à son fonctionnement (session, sécurité des formulaires)
                et aucun cookie publicitaire ou de mesure d'audience.
            </p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Droit applicable</h2>
            <p>Le présent site est soumis au droit français. En cas de litige, les tribunaux français sont compétents.</p>
        </section>
    </div>
</div>
@endsection
