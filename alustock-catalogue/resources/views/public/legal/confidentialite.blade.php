{{-- resources/views/public/legal/confidentialite.blade.php
     Politique de confidentialité (RGPD). Valeurs dans config/legal.php. --}}
@extends('layouts.app')

@section('title', 'Politique de confidentialité — AluStock')

@section('breadcrumb')
    <a href="{{ route('home') }}" class="hover:text-fg transition">Accueil</a>
    <span class="mx-2 text-ink-600">›</span>
    <span class="text-ink-200 font-medium">Confidentialité</span>
@endsection

@section('content')
<div class="max-w-4xl">

    <div class="relative bg-ink-900 border border-ink-700 p-6 mb-6">
        <span class="tick tick-tl" aria-hidden="true"></span>
        <span class="tick tick-br" aria-hidden="true"></span>
        <h1 class="font-display text-2xl font-bold text-fg">Politique de confidentialité</h1>
        <p class="font-mono text-[12px] text-ink-400 mt-1">Dernière mise à jour : {{ $l['mise_a_jour'] }}</p>
    </div>

    <div class="space-y-6 text-sm text-ink-300 leading-relaxed">

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Responsable du traitement</h2>
            <p>
                {{ $l['editeur']['raison_sociale'] }}, {{ $l['editeur']['adresse'] }}.<br>
                Contact pour toute question relative aux données personnelles :
                <a href="mailto:{{ $l['donnees']['contact_rgpd'] }}" class="text-amber-400 hover:underline">{{ $l['donnees']['contact_rgpd'] }}</a>.
            </p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Navigation sur le catalogue</h2>
            <p>
                Consulter le catalogue ne demande ni compte ni formulaire. Le site ne dépose aucun cookie publicitaire ni
                de mesure d'audience. Seuls des cookies strictement nécessaires sont utilisés (session et jeton de sécurité
                des formulaires), exemptés de consentement. Les journaux techniques du serveur (adresse IP, date, page demandée)
                sont conservés pour la sécurité et le bon fonctionnement du site.
            </p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Formulaire de contact</h2>
            <ul class="space-y-2 list-disc pl-5">
                <li><strong class="text-fg">Données collectées :</strong> nom, adresse e-mail, entreprise (facultatif), sujet, message,
                    ainsi que l'adresse IP et le navigateur utilisé (anti-spam et sécurité).</li>
                <li><strong class="text-fg">Finalité :</strong> répondre à votre demande (renseignement, demande de prix).</li>
                <li><strong class="text-fg">Base légale :</strong> mesures précontractuelles et intérêt légitime de l'éditeur à répondre aux demandes reçues.</li>
                <li><strong class="text-fg">Durée de conservation :</strong> {{ $l['donnees']['duree_conservation'] }} à compter de la réception du message.</li>
                <li><strong class="text-fg">Destinataires :</strong> les équipes de l'éditeur habilitées à traiter les demandes, et
                    {{ $l['donnees']['prestataire_email'] }} (envoi des courriels). Aucune vente ni cession à des tiers.</li>
                <li><strong class="text-fg">Transferts hors Union européenne :</strong> aucun, sauf mention contraire de l'hébergeur ou du prestataire d'envoi
                    [À COMPLÉTER si applicable].</li>
            </ul>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Vos droits</h2>
            <p>
                Vous disposez d'un droit d'accès, de rectification, d'effacement, de limitation et d'opposition au traitement
                de vos données. Pour les exercer, écrivez à
                <a href="mailto:{{ $l['donnees']['contact_rgpd'] }}" class="text-amber-400 hover:underline">{{ $l['donnees']['contact_rgpd'] }}</a>
                en précisant l'adresse e-mail utilisée ; une justification d'identité peut être demandée.
                Si vous estimez, après nous avoir contactés, que vos droits ne sont pas respectés, vous pouvez introduire une réclamation
                auprès de la CNIL (<a href="https://www.cnil.fr" class="text-amber-400 hover:underline" rel="noopener" target="_blank">cnil.fr</a>).
            </p>
        </section>

        <section class="bg-ink-900 border border-ink-800 p-5">
            <h2 class="font-mono text-[11px] font-bold text-ink-200 uppercase tracking-widest mb-3">Mentions légales</h2>
            <p>Voir les <a href="{{ route('legal.mentions') }}" class="text-amber-400 hover:underline">mentions légales</a> du site.</p>
        </section>
    </div>
</div>
@endsection
