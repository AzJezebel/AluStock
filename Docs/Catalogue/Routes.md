# Catalogue

# Fonctionnalité	               URL publique	                                URL Admin
Accueil	                    /	/                                           admin
Liste gammes	            /gammes	                                        /admin/gammes
Détail gamme	            /gammes/gamme-45	                                -
Ouvrages d'une gamme	    /gammes/gamme-45/ouvrages	                        -
Liste catégories	        /categories	                                    /admin/categories
Détail catégorie	        /categories/fenetre	                                -
Ouvrages d'une catégorie    /categories/fenetre/ouvrages	                    -
Liste ouvrages	            /ouvrages	                                    /admin/ouvrages
Détail ouvrage	            /ouvrages/fenetre-coulissante-45	                -
Composition ouvrage	        /ouvrages/fenetre-coulissante-45/composition	/admin/ouvrages/1/composition
Version imprimable	        /ouvrages/fenetre-coulissante-45/print	            -
Liste composants	        /composants	                                    /admin/composants
Détail composant	        /composants/rail-haut-45	                        -
Recherche	                /recherche?q=fenetre	                            -
Autocomplétion	            /recherche/autocomplete	                            -
Téléchargement document	    /documents/5/telecharger	                        -
Export CSV public	        /exports/composants	                                -
Export CSV admin	        -	                                            /admin/exports/inventaire
Login admin	                -	                                            /admin/login





<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\{
    CatalogueController,
    SearchController,
    DocumentController,
    ExportController
};
use App\Http\Controllers\Admin\{
    DashboardController,
    GammeController,
    CategorieController,
    TypeComposantController,
    OuvrageController,
    ComposantController,
    FinitionController,
    CompositionController,
    MediaController,
    DocumentController as AdminDocumentController
};

/*
|--------------------------------------------------------------------------
| Web Routes - V2 (Orientée Métier)
|--------------------------------------------------------------------------
|
| Noms d'entités : Gamme, Catégorie, Ouvrage, Composant
| URLs sémantiques pour les utilisateurs métier.
| Pagination activée sur toutes les listes publiques (20 par défaut).
| Export CSV disponible en backoffice.
|
*/

// ================================================================
// 1. PARTIE PUBLIQUE - CATALOGUE
// ================================================================

// ------------------------------
// Accueil
// ------------------------------
Route::get('/', [CatalogueController::class, 'home'])->name('home');

// ------------------------------
// Navigation par Gamme
// ------------------------------
Route::prefix('gammes')->name('gammes.')->group(function () {
    Route::get('/', [CatalogueController::class, 'gammesIndex'])->name('index');
    Route::get('/{gamme:slug}', [CatalogueController::class, 'gammeShow'])->name('show');
    Route::get('/{gamme:slug}/ouvrages', [CatalogueController::class, 'ouvragesByGamme'])->name('ouvrages');
});

// ------------------------------
// Navigation par Catégorie
// ------------------------------
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CatalogueController::class, 'categoriesIndex'])->name('index');
    Route::get('/{categorie:slug}', [CatalogueController::class, 'categorieShow'])->name('show');
    Route::get('/{categorie:slug}/ouvrages', [CatalogueController::class, 'ouvragesByCategorie'])->name('ouvrages');
});

// ------------------------------
// Fiches Ouvrages (cœur du catalogue)
// ------------------------------
Route::prefix('ouvrages')->name('ouvrages.')->group(function () {
    Route::get('/', [CatalogueController::class, 'ouvragesIndex'])->name('index');
    Route::get('/{ouvrage:slug}', [CatalogueController::class, 'ouvrageShow'])->name('show');
    Route::get('/{ouvrage:slug}/composition', [CatalogueController::class, 'ouvrageComposition'])->name('composition');
    Route::get('/{ouvrage:slug}/documents', [CatalogueController::class, 'ouvrageDocuments'])->name('documents');
    Route::get('/{ouvrage:slug}/print', [CatalogueController::class, 'ouvragePrint'])->name('print');
});

// ------------------------------
// Fiches Composants (détail technique)
// ------------------------------
Route::prefix('composants')->name('composants.')->group(function () {
    Route::get('/', [CatalogueController::class, 'composantsIndex'])->name('index');
    Route::get('/{composant:slug}', [CatalogueController::class, 'composantShow'])->name('show');
});

// ------------------------------
// Recherche
// ------------------------------
Route::prefix('recherche')->name('search.')->group(function () {
    Route::get('/', [SearchController::class, 'index'])->name('index');
    Route::post('/autocomplete', [SearchController::class, 'autocomplete'])->name('autocomplete');
});

// ------------------------------
// Téléchargements publics
// ------------------------------
Route::get('/documents/{document}/telecharger', [DocumentController::class, 'download'])
    ->name('documents.download');

// ------------------------------
// Export CSV (Public)
// ------------------------------
Route::prefix('exports')->name('exports.')->group(function () {
    Route::get('/composants', [ExportController::class, 'composants'])->name('composants');
    Route::get('/ouvrages', [ExportController::class, 'ouvrages'])->name('ouvrages');
    Route::get('/gammes', [ExportController::class, 'gammes'])->name('gammes');
});

// ------------------------------
// Pages statiques
// ------------------------------
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/confidentialite', 'pages.privacy')->name('privacy');


// ================================================================
// 2. PARTIE ADMIN - BACKOFFICE (Blade)
// ================================================================

Route::prefix('admin')->name('admin.')->group(function () {

    // ------------------------------
    // Login (sans Breeze/Jetstream)
    // ------------------------------
    Route::get('/login', [DashboardController::class, 'loginForm'])->name('login');
    Route::post('/login', [DashboardController::class, 'login'])->name('login.post');
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

    // ------------------------------
    // Routes protégées par middleware personnalisé
    // ------------------------------
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ==============================
        // GESTION DES GAMMES (CRUD)
        // ==============================
        Route::prefix('gammes')->name('gammes.')->group(function () {
            Route::get('/', [GammeController::class, 'index'])->name('index');
            Route::get('/creer', [GammeController::class, 'create'])->name('create');
            Route::post('/', [GammeController::class, 'store'])->name('store');
            Route::get('/{gamme}/editer', [GammeController::class, 'edit'])->name('edit');
            Route::put('/{gamme}', [GammeController::class, 'update'])->name('update');
            Route::delete('/{gamme}', [GammeController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [GammeController::class, 'reorder'])->name('reorder');

            // Export CSV
            Route::get('/export', [GammeController::class, 'export'])->name('export');
        });

        // ==============================
        // GESTION DES CATÉGORIES (CRUD)
        // ==============================
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategorieController::class, 'index'])->name('index');
            Route::get('/creer', [CategorieController::class, 'create'])->name('create');
            Route::post('/', [CategorieController::class, 'store'])->name('store');
            Route::get('/{categorie}/editer', [CategorieController::class, 'edit'])->name('edit');
            Route::put('/{categorie}', [CategorieController::class, 'update'])->name('update');
            Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('destroy');

            // Export CSV
            Route::get('/export', [CategorieController::class, 'export'])->name('export');
        });

        // ==============================
        // GESTION DES TYPES DE COMPOSANT (CRUD)
        // ==============================
        Route::prefix('types-composant')->name('types-composant.')->group(function () {
            Route::get('/', [TypeComposantController::class, 'index'])->name('index');
            Route::get('/creer', [TypeComposantController::class, 'create'])->name('create');
            Route::post('/', [TypeComposantController::class, 'store'])->name('store');
            Route::get('/{type}/editer', [TypeComposantController::class, 'edit'])->name('edit');
            Route::put('/{type}', [TypeComposantController::class, 'update'])->name('update');
            Route::delete('/{type}', [TypeComposantController::class, 'destroy'])->name('destroy');
        });

        // ==============================
        // GESTION DES OUVRAGES (CRUD)
        // ==============================
        Route::prefix('ouvrages')->name('ouvrages.')->group(function () {
            Route::get('/', [OuvrageController::class, 'index'])->name('index');
            Route::get('/creer', [OuvrageController::class, 'create'])->name('create');
            Route::post('/', [OuvrageController::class, 'store'])->name('store');
            Route::get('/{ouvrage}/editer', [OuvrageController::class, 'edit'])->name('edit');
            Route::put('/{ouvrage}', [OuvrageController::class, 'update'])->name('update');
            Route::delete('/{ouvrage}', [OuvrageController::class, 'destroy'])->name('destroy');

            // Export CSV
            Route::get('/export', [OuvrageController::class, 'export'])->name('export');

            // Gestion de la composition (sous-ressource)
            Route::prefix('/{ouvrage}/composition')->name('composition.')->group(function () {
                Route::get('/', [CompositionController::class, 'index'])->name('index');
                Route::post('/', [CompositionController::class, 'store'])->name('store');
                Route::put('/{composant}', [CompositionController::class, 'update'])->name('update');
                Route::delete('/{composant}', [CompositionController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [CompositionController::class, 'reorder'])->name('reorder');

                // Caractéristiques EAV du composant dans cet ouvrage
                Route::prefix('/{composant}/caracteristiques')->name('caracteristiques.')->group(function () {
                    Route::post('/', [CompositionController::class, 'storeCaracteristique'])->name('store');
                    Route::delete('/{caracteristique}', [CompositionController::class, 'destroyCaracteristique'])->name('destroy');
                });
            });
        });

        // ==============================
        // GESTION DES COMPOSANTS (CRUD)
        // ==============================
        Route::prefix('composants')->name('composants.')->group(function () {
            Route::get('/', [ComposantController::class, 'index'])->name('index');
            Route::get('/creer', [ComposantController::class, 'create'])->name('create');
            Route::post('/', [ComposantController::class, 'store'])->name('store');
            Route::get('/{composant}/editer', [ComposantController::class, 'edit'])->name('edit');
            Route::put('/{composant}', [ComposantController::class, 'update'])->name('update');
            Route::delete('/{composant}', [ComposantController::class, 'destroy'])->name('destroy');

            // Export CSV
            Route::get('/export', [ComposantController::class, 'export'])->name('export');

            // Gestion des caractéristiques EAV du composant
            Route::prefix('/{composant}/caracteristiques')->name('caracteristiques.')->group(function () {
                Route::post('/', [ComposantController::class, 'storeCaracteristique'])->name('store');
                Route::delete('/{caracteristique}', [ComposantController::class, 'destroyCaracteristique'])->name('destroy');
                Route::post('/reorder', [ComposantController::class, 'reorderCaracteristiques'])->name('reorder');
            });
        });

        // ==============================
        // GESTION DES FINITIONS (CRUD)
        // ==============================
        Route::prefix('finitions')->name('finitions.')->group(function () {
            Route::get('/', [FinitionController::class, 'index'])->name('index');
            Route::get('/creer', [FinitionController::class, 'create'])->name('create');
            Route::post('/', [FinitionController::class, 'store'])->name('store');
            Route::get('/{finition}/editer', [FinitionController::class, 'edit'])->name('edit');
            Route::put('/{finition}', [FinitionController::class, 'update'])->name('update');
            Route::delete('/{finition}', [FinitionController::class, 'destroy'])->name('destroy');

            // Export CSV
            Route::get('/export', [FinitionController::class, 'export'])->name('export');
        });

        // ==============================
        // GESTION DES MÉDIAS (Images)
        // ==============================
        Route::prefix('medias')->name('medias.')->group(function () {
            Route::get('/', [MediaController::class, 'index'])->name('index');
            Route::post('/upload', [MediaController::class, 'store'])->name('store');
            Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
        });

        // ==============================
        // GESTION DES DOCUMENTS (PDF)
        // ==============================
        Route::prefix('documents')->name('documents.')->group(function () {
            Route::get('/', [AdminDocumentController::class, 'index'])->name('index');
            Route::post('/upload', [AdminDocumentController::class, 'store'])->name('store');
            Route::delete('/{document}', [AdminDocumentController::class, 'destroy'])->name('destroy');
        });

        // ==============================
        // EXPORT CSV (Admin uniquement)
        // ==============================
        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/composants', [ExportController::class, 'composantsAdmin'])->name('composants');
            Route::get('/ouvrages', [ExportController::class, 'ouvragesAdmin'])->name('ouvrages');
            Route::get('/gammes', [ExportController::class, 'gammesAdmin'])->name('gammes');
            Route::get('/inventaire', [ExportController::class, 'inventaire'])->name('inventaire');
        });

    }); // Fin middleware admin
}); // Fin prefix admin