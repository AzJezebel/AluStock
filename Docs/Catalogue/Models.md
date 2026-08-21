# Catalogue

1. Gamme
2. Categorie
3. TypeComposant
4. Ouvrage (dépend de Gamme + Categorie)
5. Composant (dépend de TypeComposant + Gamme)
6. Finition
7. CompositionOuvrage (table pivot avec attributs)
8. ComposantFinition (table pivot)
9. Caracteristique (polymorphique)
10. Media (avec MediaMorph)
11. Document (avec DocumentAssociation)


# Views structure

resources/views/
├── layouts/
│   └── app.blade.php          ← Layout principal
├── admin/
│   ├── gammes/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── categories/
│   ├── ouvrages/
│   ├── composants/
│   └── dashboard.blade.php
├── public/
│   ├── home.blade.php
│   ├── gammes/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── categories/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── ouvrages/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   ├── composition.blade.php
│   │   └── print.blade.php
│   ├── composants/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   │ 
│   ├── search/
│   │   └── index.blade.php         # Résultats transverses
└── admin/
    └── login.blade.php


# Old thought

resources/views/
├── gammes/
│   └── show.blade.php          # Couche haute
├── types-ouvrage/
│   └── show.blade.php          # Couche haute
├── ouvrages/
│   ├── index.blade.php         # Liste (centrale)
│   ├── show.blade.php          # Fiche (centrale)
│   └── composition.blade.php   # Composition (basse)
├── composants/
│   └── show.blade.php          # Fiche technique (basse)
└── search/
    └── index.blade.php         # Résultats transverses


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