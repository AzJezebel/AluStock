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


alustock-catalogue/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
CRUD protégé par admin.auth
│   │   │   │   ├── AuthController.php
durci : throttle, hash_equals, session régénérée
│   │   │   │   ├── CaracteristiqueController.php
│   │   │   │   ├── CategorieController.php
│   │   │   │   ├── ComposantController.php
│   │   │   │   ├── CompositionController.php
méthode index morte retirée
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── MediaController.php
│   │   │   │   ├── OuvrageController.php
│   │   │   │   └── TypeComposantController.php
│   │   │   ├── Public/
lecture seule, résolution par slug
│   │   │   │   ├── CategorieController.php
méthodes mortes retirées
│   │   │   │   ├── ComposantController.php
│   │   │   │   ├── ContactController.php
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── OuvrageController.php
allégé : plus d'export ni d'autocomplete
│   │   │   │   └── SearchController.php
scope recherche, OR groupés
│   │   │   └── Controller.php
│   │   ├── Middleware/
│   │   │   └── AdminAuth.php
│   │   ├── Requests/
│   │   │   ├── Admin/
│   │   │   │   ├── StoreCaracteristiqueRequest.php
jamais utilisée
│   │   │   │   ├── StoreComposantRequest.php
│   │   │   │   ├── StoreCompositionRequest.php
jamais utilisée
│   │   │   │   ├── StoreMediaRequest.php
jamais utilisée
│   │   │   │   ├── StoreOuvrageRequest.php
│   │   │   │   ├── UpdateComposantRequest.php
│   │   │   │   └── UpdateOuvrageRequest.php
│   │   │   └── ContactRequest.php
│   │   └── ViewComposers/
│   │       └── SidebarComposer.php
lié à public.partials.sidebar-menu
│   ├── Mail/
│   │   ├── ContactAcknowledgementMail.php
│   │   └── ContactMessageMail.php
│   ├── Models/
│   │   ├── Caracteristique.php
│   │   ├── Categorie.php
│   │   ├── Composant.php
slug unique, scopeRecherche
│   │   ├── ContactMessage.php
trace des messages du formulaire
│   │   ├── Document.php
│   │   ├── Finition.php
│   │   ├── Media.php
│   │   ├── Ouvrage.php
slug unique, scopeRecherche, PIVOT_COMPOSITION
│   │   ├── TypeComposant.php
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
enregistre le composer
├── bootstrap/
│   ├── cache/
│   │   ├── .gitignore
│   │   ├── packages.php
│   │   └── services.php
│   ├── app.php
│   └── providers.php
├── config/
│   ├── app.php
admin_password sans valeur par défaut
│   ├── auth.php
│   ├── cache.php
│   ├── contact.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_08_19_192949_create_categories_table.php
│   │   ├── 2026_08_19_193007_create_types_composant_table.php
│   │   ├── 2026_08_19_193035_create_ouvrages_table.php
│   │   ├── 2026_08_19_193106_create_composants_table.php
│   │   ├── 2026_08_19_195837_create_composition_ouvrage_table.php
│   │   ├── 2026_08_19_195909_create_finitions_table.php
│   │   ├── 2026_08_19_195929_create_composant_finition_table.php
│   │   ├── 2026_08_19_195951_create_caracteristiques_table.php
│   │   ├── 2026_08_19_200010_create_medias_table.php
│   │   ├── 2026_08_19_200059_create_media_morph_table.php
│   │   ├── 2026_08_19_200145_create_documents_table.php
│   │   ├── 2026_08_19_200201_create_document_association_table.php
│   │   └── 2026_10_07_191413_create_contact_message_table.php
table contact_messages
│   └── seeders/
│       ├── CaracteristiqueSeeder.php
│       ├── CaracteristiquesTableSeeder.php
│       ├── CategorieSeeder.php
│       ├── CategoriesTableSeeder.php
│       ├── ComposantFinitionSeeder.php
│       ├── ComposantFinitionTableSeeder.php
│       ├── ComposantSeeder.php
│       ├── ComposantsTableSeeder.php
│       ├── CompositionOuvrageSeeder.php
│       ├── CompositionOuvrageTableSeeder.php
│       ├── DatabaseSeeder.php
appelle les *TableSeeder (iseed)
│       ├── FinitionSeeder.php
│       ├── FinitionsTableSeeder.php
│       ├── MediaMorphTableSeeder.php
│       ├── MediasTableSeeder.php
│       ├── OuvrageSeeder.php
│       ├── OuvragesTableSeeder.php
│       ├── TypeComposantSeeder.php
│       └── TypesComposantTableSeeder.php
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── auth/
│       │   │   └── login.blade.php
│       │   ├── categories/
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── index.blade.php
│       │   ├── composants/
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── index.blade.php
│       │   ├── ouvrages/
│       │   │   ├── partials/
│       │   │   │   ├── caracteristiques.blade.php
│       │   │   │   ├── composition.blade.php
│       │   │   │   └── infos.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── index.blade.php
│       │   ├── partials/
│       │   │   └── medias.blade.php
│       │   ├── types-composant/
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── index.blade.php
│       │   └── dashboard.blade.php
│       ├── emails/
│       │   ├── contact-ack.blade.php
│       │   └── contact.blade.php
│       ├── layouts/
│       │   ├── admin.blade.php
Tailwind via CDN
│       │   └── app.blade.php
Tailwind via CDN
│       └── public/
│           ├── categories/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── composants/
│           │   ├── index.blade.php
│           │   └── show.blade.php
│           ├── ouvrages/
│           │   ├── composition.blade.php
poids estimé calculé dans la vue
│           │   ├── index.blade.php
│           │   ├── print.blade.php
ajoutée (était absente)
│           │   └── show.blade.php
│           ├── partials/
│           │   ├── composant-card.blade.php
│           │   ├── logo.blade.php
│           │   ├── media-carousel.blade.php
│           │   ├── ouvrage-card.blade.php
│           │   └── sidebar-menu.blade.php
│           ├── search/
│           │   └── index.blade.php
│           ├── contact.blade.php
│           └── home.blade.php
├── routes/
│   ├── console.php
│   └── web.php
55 routes, un bloc par ressource
└── tests/
    ├── Feature/
    │   └── ExampleTest.php
    ├── Unit/
    │   └── ExampleTest.php
    └── TestCase.php


    