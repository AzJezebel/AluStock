<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identité du site (remplace « AluStock »)
    |--------------------------------------------------------------------------
    | Le logo est affiché en deux parties : « first » en blanc, « second » en
    | couleur d'accent. Change-les ici ou via le .env.
    */
    'brand' => [
        'first'  => env('VITRINE_BRAND_FIRST', 'Votre'),
        'second' => env('VITRINE_BRAND_SECOND', 'Marque'),
    ],

    'tagline' => env('VITRINE_TAGLINE', "Ouvrages en aluminium sur mesure : conception, fabrication et pose."),
    'since'   => (int) env('VITRINE_SINCE', 2005),

    'hero' => [
        'eyebrow'  => 'Réalisations sur mesure',
        'title_1'  => "L'aluminium façonné",
        'title_2'  => 'avec précision',
        'subtitle' => "Façades, verrières, garde-corps, pergolas : découvrez nos ouvrages livrés et le savoir-faire qui les rend uniques.",

        // Images du diaporama (chemins relatifs au disque « public » ou URL).
        // Vide = 3 placeholders animés. Ex : ['hero/1.jpg', 'hero/2.jpg', 'hero/3.jpg']
        'images'   => [],

        // Durée d'une diapositive (ms)
        'interval' => 6500,
    ],

    // Mots défilants sous le hero (complétés par les catégories/gammes de la base)
    'marquee' => ['Façades', 'Verrières', 'Garde-corps', 'Pergolas', 'Menuiseries', 'Escaliers', 'Sur mesure'],

    'contact' => [
        'phone'   => env('VITRINE_PHONE', '+33 (0)1 23 45 67 89'),
        'email'   => env('VITRINE_EMAIL', 'contact@exemple.fr'),
        'address' => env('VITRINE_ADDRESS', "123 Avenue de l'Industrie"),
        'city'    => env('VITRINE_CITY', '75001 Paris, France'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Médias
    |--------------------------------------------------------------------------
    | disk : disque Laravel où sont rangées les photos (php artisan storage:link
    |        pour que « public » soit accessible via /storage).
    | Une photo manquante ou cassée est toujours remplacée par un placeholder.
    */
    'media' => [
        'disk' => env('VITRINE_MEDIA_DISK', 'public'),
    ],

    // Affiche 6 ouvrages fictifs tant que la base est vide (pratique pour le design)
    'demo_when_empty' => env('VITRINE_DEMO', true),

    // Panneau flottant de test des palettes — mettre à false avant la mise en ligne
    'palette_switcher' => env('VITRINE_PALETTE_SWITCHER', true),
];
