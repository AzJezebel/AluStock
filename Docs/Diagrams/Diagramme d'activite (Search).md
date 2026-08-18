┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    DIAGRAMME D'ACTIVITÉS - RECHERCHE CATALOGUE                      │
└─────────────────────────────────────────────────────────────────────────────────────┘

                              ┌─────────────────────┐
                              │        Début        │
                              └──────────┬──────────┘
                                         │
                                         ▼
                              ┌─────────────────────┐
                              │  Page d'accueil du  │
                              │  catalogue          │
                              └──────────┬──────────┘
                                         │
                                         ▼
                              ┌─────────────────────────────────────┐
                              │      L'utilisateur choisit :        │
                              ├─────────────────────────────────────┤
                              │  [Recherche] [Filtres] [Navigation] │
                              └──────────────────┬──────────────────┘
                                                 │
                     ┌───────────────────────────┼───────────────────────────┐
                     │                           │                           │
                     ▼                           ▼                           ▼
          ┌──────────────────┐      ┌─────────────────────┐      ┌──────────────────┐
          │  Saisit un mot   │      │  Sélectionne des    │      │  Clique sur une  │
          │  clé / référence │      │  filtres :          │      │  gamme / type    │
          └────────┬─────────┘      │  - Gamme            │      └────────┬─────────┘
                   │                │  - Type ouvrage     │               │
                   │                │  - Finition         │               │
                   │                │  - Matière          │               │
                   │                └────────┬────────────┘               │
                   │                         │                            │
                   └─────────────────────────┼────────────────────────────┘
                                             │
                                             ▼
                              ┌─────────────────────────────────────┐
                              │   Exécution de la requête SQL       │
                              │   avec les critères combinés        │
                              └──────────────────┬──────────────────┘
                                                 │
                                                 ▼
                              ┌─────────────────────────────────────┐
                              │        Résultats affichés           │
                              │   Liste des modèles / pièces        │
                              └──────────────────┬──────────────────┘
                                                 │
                                                 ▼
                              ┌─────────────────────────────────────┐
                              │  L'utilisateur clique sur un        │
                              │  résultat                           │
                              └──────────────────┬──────────────────┘
                                                 │
                                                 ▼
                              ┌─────────────────────────────────────┐
                              │      Fiche détaillée affichée       │
                              │  - Description du modèle            │
                              │  - Composition (pièces + qtés)      │
                              │  - Caractéristiques techniques      │
                              │  - Documents téléchargeables        │
                              └──────────────────┬──────────────────┘
                                                 │
                                                 ▼
                              ┌─────────────────────────────────────┐
                              │  L'utilisateur peut :               │
                              ├─────────────────────────────────────┤
                              │  [Télécharger PDF] [Revenir à la    │
                              │   recherche] [Voir pièces détachées]│
                              └──────────────────┬──────────────────┘
                                                 │
                                                 ▼
                              ┌─────────────────────────────────────┐
                              │            Fin                      │
                              └─────────────────────────────────────┘




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