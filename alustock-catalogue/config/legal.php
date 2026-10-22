<?php

/*
|--------------------------------------------------------------------------
| Informations légales (mentions légales + confidentialité)
|--------------------------------------------------------------------------
| Tout ce qui est « [À COMPLÉTER] » doit être remplacé par les infos réelles
| de l'entreprise (à demander au tuteur). Un seul endroit à modifier : les
| deux pages publiques lisent ce fichier.
*/

return [

    // --- Éditeur du site ---
    'editeur' => [
        'raison_sociale'   => '[À COMPLÉTER : raison sociale]',
        'forme_juridique'  => '[À COMPLÉTER : SAS / SARL / …]',
        'capital'          => '[À COMPLÉTER : capital social, en €]',
        'adresse'          => '[À COMPLÉTER : adresse du siège social]',
        'siren'            => '[À COMPLÉTER : n° SIREN / RCS + ville]',
        'tva'              => '[À COMPLÉTER : n° TVA intracommunautaire]',
        'email'            => env('CONTACT_RECIPIENT_ADDRESS', 'contact@alustock.fr'),
        'telephone'        => '+xxxxxxxxx',
        'directeur_publication' => '[À COMPLÉTER : nom du directeur de la publication]',
    ],

    // --- Hébergeur ---
    'hebergeur' => [
        'nom'       => '[À COMPLÉTER : nom de l\'hébergeur]',
        'adresse'   => '[À COMPLÉTER : adresse de l\'hébergeur]',
        'telephone' => '[À COMPLÉTER : téléphone de l\'hébergeur]',
    ],

    // --- Réalisation (optionnel : mettre null pour masquer) ---
    'realisation' => 'Site réalisé par ASSAD Jézébel dans le cadre d\'un stage de formation (2026).',

    // --- Données personnelles ---
    'donnees' => [
        // Personne ou service à qui écrire pour exercer ses droits.
        'contact_rgpd'       => env('CONTACT_RECIPIENT_ADDRESS', 'contact@alustock.fr'),
        // Durée de conservation des messages du formulaire de contact.
        'duree_conservation' => '[À COMPLÉTER : ex. 12 mois]',
        // Prestataire d'envoi d'emails (SMTP).
        'prestataire_email'  => '[À COMPLÉTER : prestataire d\'envoi des courriels]',
    ],

    'mise_a_jour' => '[À COMPLÉTER : date de dernière mise à jour]',
];
