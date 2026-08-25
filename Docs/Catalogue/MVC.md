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
│   └── app.blade.php                # Layout principal
├── public/
│   ├── home.blade.php               # Page d'accueil
│   ├── gammes/
│   │   ├── index.blade.php          # Liste des gammes
│   │   ├── show.blade.php           # Détail d'une gamme
│   │   ├── ouvrages.blade.php       # Ouvrages d'une gamme
│   │   └── composants.blade.php     # Composants d'une gamme
│   ├── categories/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── ouvrages/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── composition.blade.php
│   ├── composants/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   └── search/
│       └── index.blade.php
├── admin/
│   ├── login.blade.php              # Page de login
│   ├── dashboard.blade.php
│   ├── gammes/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── ... (autres CRUD)
└── pages/
    ├── legal.blade.php              # Mentions légales
    └── privacy.blade.php            # Confidentialité

# Controllers 

app/Http/Controllers/
│
├── Controller.php                          # Contrôleur de base
│
├── Admin/                                  # Backoffice (authentifié)
│   ├── DashboardController.php             # Tableau de bord admin
│   ├── GammeController.php                 # CRUD Gammes
│   ├── CategorieController.php             # CRUD Catégories
│   ├── TypeComposantController.php         # CRUD Types de composant
│   ├── OuvrageController.php               # CRUD Ouvrages
│   ├── ComposantController.php             # CRUD Composants
│   ├── FinitionController.php              # CRUD Finitions
│   ├── CompositionController.php           # Gestion de la composition (Ouvrage ↔ Composant)
│   ├── CaracteristiqueController.php       # Gestion des caractéristiques EAV
│   ├── MediaController.php                 # Gestion des médias (upload, suppression)
│   └── DocumentController.php              # Gestion des documents (upload, suppression)
│
├── Public/                                 # Partie publique (catalogue)
│   ├── HomeController.php                  # Page d'accueil du catalogue
│   ├── GammeController.php                 # Consultation des gammes
│   ├── CategorieController.php             # Consultation des catégories
│   ├── OuvrageController.php               # Consultation des ouvrages
│   ├── ComposantController.php             # Consultation des composants
│   ├── SearchController.php                # Recherche avancée
│   ├── DocumentController.php              # Téléchargement de documents
│   └── ExportController.php                # Export CSV (optionnel)
│
└── Auth/                                   # Authentification (optionnel)
    ├── LoginController.php                 # Connexion
    └── LogoutController.php                # Déconnexion

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