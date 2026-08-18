
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\{
    CatalogueController,
    SearchController,
    DocumentController
};
use App\Http\Controllers\Admin\{
    DashboardController,
    GammeController,
    TypeOuvrageController,
    TypePieceController,
    ModeleController,
    PieceController,
    FinitionController,
    CompositionController,
    MediaController,
    DocumentController as AdminDocumentController
};

/*
|--------------------------------------------------------------------------
| Web Routes - (Orientée Métier)
|--------------------------------------------------------------------------
|
| Architecture organisée par cas d'usage métier plutôt que par entité.
| URLs sémantiques et naturelles pour l'utilisateur.
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
    Route::get('/{gamme:slug}/modeles', [CatalogueController::class, 'modelesByGamme'])->name('modeles');
});

// ------------------------------
// Navigation par Type d'Ouvrage
// ------------------------------
Route::prefix('types-ouvrage')->name('types-ouvrage.')->group(function () {
    Route::get('/', [CatalogueController::class, 'typesOuvrageIndex'])->name('index');
    Route::get('/{type:slug}', [CatalogueController::class, 'typeOuvrageShow'])->name('show');
    Route::get('/{type:slug}/modeles', [CatalogueController::class, 'modelesByType'])->name('modeles');
});

// ------------------------------
// Fiches Modèles (cœur du catalogue)
// ------------------------------
Route::prefix('modeles')->name('modeles.')->group(function () {
    Route::get('/', [CatalogueController::class, 'modelesIndex'])->name('index');
    Route::get('/{modele:slug}', [CatalogueController::class, 'modeleShow'])->name('show');
    Route::get('/{modele:slug}/composition', [CatalogueController::class, 'modeleComposition'])->name('composition');
    Route::get('/{modele:slug}/documents', [CatalogueController::class, 'modeleDocuments'])->name('documents');
});

// ------------------------------
// Fiches Pièces (détail technique)
// ------------------------------
Route::prefix('pieces')->name('pieces.')->group(function () {
    Route::get('/', [CatalogueController::class, 'piecesIndex'])->name('index');
    Route::get('/{piece:slug}', [CatalogueController::class, 'pieceShow'])->name('show');
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
// Pages statiques (optionnel)
// ------------------------------
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/confidentialite', 'pages.privacy')->name('privacy');


// ================================================================
// 2. PARTIE ADMIN - BACKOFFICE 
// ================================================================

Route::prefix('admin')->name('admin.')->group(function () {


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
        });

        // ==============================
        // GESTION DES TYPES D'OUVRAGE (CRUD)
        // ==============================
        Route::prefix('types-ouvrage')->name('types-ouvrage.')->group(function () {
            Route::get('/', [TypeOuvrageController::class, 'index'])->name('index');
            Route::get('/creer', [TypeOuvrageController::class, 'create'])->name('create');
            Route::post('/', [TypeOuvrageController::class, 'store'])->name('store');
            Route::get('/{type}/editer', [TypeOuvrageController::class, 'edit'])->name('edit');
            Route::put('/{type}', [TypeOuvrageController::class, 'update'])->name('update');
            Route::delete('/{type}', [TypeOuvrageController::class, 'destroy'])->name('destroy');
        });

        // ==============================
        // GESTION DES TYPES DE PIÈCE (CRUD)
        // ==============================
        Route::prefix('types-piece')->name('types-piece.')->group(function () {
            Route::get('/', [TypePieceController::class, 'index'])->name('index');
            Route::get('/creer', [TypePieceController::class, 'create'])->name('create');
            Route::post('/', [TypePieceController::class, 'store'])->name('store');
            Route::get('/{type}/editer', [TypePieceController::class, 'edit'])->name('edit');
            Route::put('/{type}', [TypePieceController::class, 'update'])->name('update');
            Route::delete('/{type}', [TypePieceController::class, 'destroy'])->name('destroy');
        });

        // ==============================
        // GESTION DES MODÈLES (CRUD)
        // ==============================
        Route::prefix('modeles')->name('modeles.')->group(function () {
            Route::get('/', [ModeleController::class, 'index'])->name('index');
            Route::get('/creer', [ModeleController::class, 'create'])->name('create');
            Route::post('/', [ModeleController::class, 'store'])->name('store');
            Route::get('/{modele}/editer', [ModeleController::class, 'edit'])->name('edit');
            Route::put('/{modele}', [ModeleController::class, 'update'])->name('update');
            Route::delete('/{modele}', [ModeleController::class, 'destroy'])->name('destroy');

            // Gestion de la composition (sous-ressource)
            Route::prefix('/{modele}/composition')->name('composition.')->group(function () {
                Route::get('/', [CompositionController::class, 'index'])->name('index');
                Route::post('/', [CompositionController::class, 'store'])->name('store');
                Route::put('/{piece}', [CompositionController::class, 'update'])->name('update');
                Route::delete('/{piece}', [CompositionController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [CompositionController::class, 'reorder'])->name('reorder');

                // Caractéristiques EAV de la pièce dans ce modèle
                Route::prefix('/{piece}/caracteristiques')->name('caracteristiques.')->group(function () {
                    Route::post('/', [CompositionController::class, 'storeCaracteristique'])->name('store');
                    Route::delete('/{caracteristique}', [CompositionController::class, 'destroyCaracteristique'])->name('destroy');
                });
            });
        });

        // ==============================
        // GESTION DES PIÈCES (CRUD)
        // ==============================
        Route::prefix('pieces')->name('pieces.')->group(function () {
            Route::get('/', [PieceController::class, 'index'])->name('index');
            Route::get('/creer', [PieceController::class, 'create'])->name('create');
            Route::post('/', [PieceController::class, 'store'])->name('store');
            Route::get('/{piece}/editer', [PieceController::class, 'edit'])->name('edit');
            Route::put('/{piece}', [PieceController::class, 'update'])->name('update');
            Route::delete('/{piece}', [PieceController::class, 'destroy'])->name('destroy');

            // Gestion des caractéristiques EAV de la pièce
            Route::prefix('/{piece}/caracteristiques')->name('caracteristiques.')->group(function () {
                Route::post('/', [PieceController::class, 'storeCaracteristique'])->name('store');
                Route::delete('/{caracteristique}', [PieceController::class, 'destroyCaracteristique'])->name('destroy');
                Route::post('/reorder', [PieceController::class, 'reorderCaracteristiques'])->name('reorder');
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

    }); // Fin middleware admin
}); // Fin prefix admin