# Catalogue

resources/views/
│
├── layouts/
│   ├── app.blade.php                    # Layout public (site catalogue)
│   └── admin.blade.php                  # Layout admin (backoffice)
│
├── partials/
│   └── logo.blade.php                   # Logo réutilisable (public + admin)
│
├── public/                              # Site public
│   ├── home.blade.php                   # Page d'accueil (hero, catégories, gammes, stats)
│   │
│   ├── ouvrages/
│   │   ├── index.blade.php              # Liste des ouvrages (grille + thumbnails)
│   │   ├── show.blade.php               # Détail ouvrage (carrousel, specs, composition)
│   │   ├── composition.blade.php        # Composition détaillée
│   │   └── print.blade.php              # Version imprimable
│   │
│   ├── composants/
│   │   ├── index.blade.php              # Liste des composants (grille + thumbnails)
│   │   └── show.blade.php               # Détail composant (carrousel, specs)
│   │
│   ├── gammes/
│   │   ├── index.blade.php              # Liste des gammes
│   │   └── show.blade.php               # Détail d'une gamme
│   │
│   ├── categories/
│   │   ├── index.blade.php              # Liste des catégories
│   │   └── show.blade.php               # Détail d'une catégorie
│   │
│   ├── search/
│   │   └── index.blade.php              # Résultats de recherche
│   │
│   └── partials/
│       ├── sidebar-menu.blade.php       # Menu latéral (catégories + gammes + liens)
│       └── media-carousel.blade.php     # Carrousel + lightbox + zoom
│
├── admin/                               # Backoffice admin
│   ├── auth/
│   │   └── login.blade.php              # Page de connexion
│   │
│   ├── dashboard.blade.php              # Dashboard avec sections (Contenu, Classification)
│   │
│   ├── ouvrages/
│   │   ├── index.blade.php              # Liste avec tri, filtres, pagination
│   │   ├── create.blade.php             # Création (avec modals composition/carac/médias)
│   │   ├── edit.blade.php               # Édition (uniforme avec create)
│   │   └── composition.blade.php        # (optionnel) vue dédiée composition
│   │
│   ├── composants/
│   │   ├── index.blade.php              # Liste avec tri, filtres
│   │   ├── create.blade.php             # Création (avec conditionnel profilé)
│   │   └── edit.blade.php               # Édition
│   │
│   ├── categories/
│   │   ├── index.blade.php              # Liste avec tri + count
│   │   ├── create.blade.php             # Création
│   │   └── edit.blade.php               # Édition
│   │
│   ├── types-composant/                 # ⚠️ NOUVEAU
│   │   ├── index.blade.php              # Liste avec tri + count de composants
│   │   ├── create.blade.php             # Création
│   │   └── edit.blade.php               # Édition
│   │
│   └── partials/
│       └── medias.blade.php             # Section médias (upload AJAX + grille)
│//TODO
└── pages/                               # Pages statiques
    ├── legal.blade.php                  # Mentions légales
    └── privacy.blade.php                # Politique de confidentialité

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


┌──────────────────────────────────────────────┐
│ Bandeau utilitaire (ink-950)                 │
├──────────────────────────────────────────────┤
│ Header (logo + recherche + CTA)              │
├──────────────────────────────────────────────┤
│ Bandeau utilitaire (ink-950)                 │
├──────────────────────────────────────────────┤
│ Contenu principal                            │
│  ┌───────────┬──────────────────────────┐   │
│  │ Sidebar   │  @yield('content')       │   │
│  │ (sticky)  │                          │   │
│  │           │                          │   │
│  │ - Catég.  │                          │   │
│  │ - Gammes  │                          │   │
│  │ - Liens   │                          │   │
│  └───────────┴──────────────────────────┘   │
├──────────────────────────────────────────────┤
│ Footer (ink-950)                             │
└──────────────────────────────────────────────┘

┌──────────────────────────────────────────────┐
│ Header admin (admin-900)                     │
│  Logo + Nav (Dashboard/Ouvrages/Composants/ │
│              Catégories/Types) + Logout     │
├──────────────────────────────────────────────┤
│ Contenu principal                            │
│  @yield('content')                           │
├──────────────────────────────────────────────┤
│ Footer admin (white, border-top)             │
└──────────────────────────────────────────────┘


Partial	                                                Utilisé dans	                              Rôle
partials/logo.blade.php	                       layouts/app, layouts/admin	                   Logo AluStock
public/partials/sidebar-menu.blade.php	       layouts/app	                                   Menu latéral public
public/partials/media-carousel.blade.php	   ouvrages/show, composants/show	               Carrousel + lightbox
admin/partials/medias.blade.php	               admin/ouvrages/edit, admin/composants/edit	   Upload + grille médias