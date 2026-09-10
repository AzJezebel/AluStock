# Catalogue

resources/views/
├── gammes/
│   └── show.blade.php          # Couche haute
├── types-ouvrage/
│   └── show.blade.php          # Couche haute
├── modeles/
│   ├── index.blade.php         # Liste (centrale)
│   ├── show.blade.php          # Fiche (centrale)
│   └── composition.blade.php   # Composition (basse)
├── pieces/
│   └── show.blade.php          # Fiche technique (basse)
└── search/
    └── index.blade.php         # Résultats transverses

resources/views/admin/
├── layouts/
│   └── admin.blade.php
├── dashboard.blade.php
├── ouvrages/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── partials/
│       ├── infos.blade.php
│       ├── composition.blade.php
│       ├── caracteristiques.blade.php
│       └── medias.blade.php
├── composants/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── auth/
    └── login.blade.php

// 1. Navigation haute
Route::get('/gammes/{gamme:slug}', [GammeController::class, 'show']);
Route::get('/types-ouvrage/{type:slug}', [TypeOuvrageController::class, 'show']);

// 2. Cœur du catalogue
Route::get('/modeles/{modele:slug}', [ModeleController::class, 'show']);

// 3. Détail (via le modèle)
Route::get('/modeles/{modele:slug}/composition', [ModeleController::class, 'composition']);
Route::get('/modeles/{modele:slug}/pieces/{piece:slug}', [PieceController::class, 'show']);

// 4. Recherche directe (pour les ingénieurs)
Route::get('/recherche', [SearchController::class, 'index']);