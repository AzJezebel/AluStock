<?php

use App\Http\Controllers\Public\CategorieController;
use App\Http\Controllers\Public\ComposantController;
use App\Http\Controllers\Public\GammeController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\OuvrageController;
use App\Http\Controllers\Public\SearchController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\CaracteristiqueController as AdminCaracteristiqueController;
use App\Http\Controllers\Admin\ComposantController as AdminComposantController;
use App\Http\Controllers\Admin\CompositionController as AdminCompositionController;
use App\Http\Controllers\Admin\OuvrageController as AdminOuvrageController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gammes', [GammeController::class, 'index'])->name('gammes.index');
Route::get('/gammes/{gamme}', [GammeController::class, 'show'])->name('gammes.show');
Route::get('/gammes/{gamme}/ouvrages', [GammeController::class, 'ouvrages'])->name('gammes.ouvrages');
Route::get('/gammes/{gamme}/composants', [GammeController::class, 'composants'])->name('gammes.composants');


// // Catégories
// Route::prefix('categories')->name('categories.')->group(function () {
//     Route::get('/', [CategorieController::class, 'index'])->name('index');
//     Route::get('/{categorie:slug}', [CategorieController::class, 'show'])->name('show');
//     Route::get('/{categorie:slug}/ouvrages', [CategorieController::class, 'ouvrages'])->name('ouvrages');
//     Route::get('/{categorie:slug}/composants', [CategorieController::class, 'composants'])->name('composants');
//     Route::get('/export', [CategorieController::class, 'export'])->name('export');
// });

// Catégories (liste uniquement)
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategorieController::class, 'index'])->name('index');
    Route::get('/{categorie:slug}', [CategorieController::class, 'show'])->name('show');
    Route::get('/export', [CategorieController::class, 'export'])->name('export');
});

// Ouvrages (avec filtres)
Route::prefix('ouvrages')->name('ouvrages.')->group(function () {
    Route::get('/', [OuvrageController::class, 'index'])->name('index');
    Route::get('/{ouvrage:slug}', [OuvrageController::class, 'show'])->name('show');
    Route::get('/{ouvrage:slug}/composition', [OuvrageController::class, 'composition'])->name('composition');
    Route::get('/{ouvrage:slug}/print', [OuvrageController::class, 'print'])->name('print');
});


// ============================================================
// COMPOSANTS (public)
// ============================================================
Route::prefix('composants')->name('composants.')->group(function () {
    Route::get('/', [ComposantController::class, 'index'])->name('index');
    Route::get('/{composant:slug}', [ComposantController::class, 'show'])->name('show');
});



// Recherche
Route::prefix('search')->name('search.')->group(function () {
    Route::get('/', [SearchController::class, 'index'])->name('index');
    Route::get('/autocomplete', [SearchController::class, 'autocomplete'])->name('autocomplete');
    Route::post('/quick', [SearchController::class, 'quickSearch'])->name('quick');
});

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Auth (pas protégé)
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protégé par middleware admin
    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Route::resource('ouvrages', OuvrageController::class)
        //     ->parameters(['ouvrages' => 'ouvrage:slug'])
        //     ->except(['show']);

        Route::prefix('ouvrages')->name('ouvrages.')->group(function () {
            Route::get('/', [AdminOuvrageController::class, 'index'])->name('index');
            Route::get('/creer', [AdminOuvrageController::class, 'create'])->name('create');
            Route::post('/', [AdminOuvrageController::class, 'store'])->name('store');

            Route::get('/{ouvrage:slug}/editer', [AdminOuvrageController::class, 'edit'])->name('edit');
            Route::put('/{ouvrage:slug}', [AdminOuvrageController::class, 'update'])->name('update');
            Route::delete('/{ouvrage:slug}', [AdminOuvrageController::class, 'destroy'])->name('destroy');

            // Composition
            Route::prefix('/{ouvrage:slug}/composition')->name('composition.')->group(function () {
                Route::post('/', [AdminCompositionController::class, 'store'])->name('store');
                Route::post('/nouveau', [AdminCompositionController::class, 'storeNew'])->name('store-new');
                Route::put('/{composant}', [AdminCompositionController::class, 'update'])->name('update');
                Route::delete('/{composant}', [AdminCompositionController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [AdminCompositionController::class, 'reorder'])->name('reorder');
            });

            // Caractéristiques
            Route::prefix('/{ouvrage:slug}/caracteristiques')->name('caracteristiques.')->group(function () {
                Route::post('/', [AdminCaracteristiqueController::class, 'store'])->name('store');
                Route::put('/{caracteristique}', [AdminCaracteristiqueController::class, 'update'])->name('update');
                Route::delete('/{caracteristique}', [AdminCaracteristiqueController::class, 'destroy'])->name('destroy');
                Route::post('/reorder', [AdminCaracteristiqueController::class, 'reorder'])->name('reorder');
            });

            // Médias
            Route::prefix('/{ouvrage:slug}/medias')->name('medias.')->group(function () {
                Route::post('/upload', [AdminMediaController::class, 'store'])->name('store');
                Route::delete('/{media}', [AdminMediaController::class, 'destroy'])->name('destroy');
                Route::post('/{media}/principal', [AdminMediaController::class, 'setPrincipal'])->name('principal');
                Route::post('/reorder', [AdminMediaController::class, 'reorder'])->name('reorder');
            });
        });

        Route::resource('composants', AdminComposantController::class);
        
        Route::get('/gammes', [DashboardController::class, 'gammes'])->name('gammes.index');
        Route::get('/categories', [DashboardController::class, 'categories'])->name('categories.index');
    }); 
});

//     Route::middleware('admin.auth')->group(function () {
        
//         // Dashboard
//         Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

//         // ============================================================
//         // OUVRAGES
//         // ============================================================
//         Route::prefix('ouvrages')->name('ouvrages.')->group(function () {
//             Route::get('/', [OuvrageController::class, 'index'])->name('index');
//             Route::get('/creer', [OuvrageController::class, 'create'])->name('create');
//             Route::post('/', [OuvrageController::class, 'store'])->name('store');
//             Route::get('/{ouvrage}/editer', [OuvrageController::class, 'edit'])->name('edit');
//             Route::put('/{ouvrage}', [OuvrageController::class, 'update'])->name('update');
//             Route::delete('/{ouvrage}', [OuvrageController::class, 'destroy'])->name('destroy');

//             // Composition
//             Route::prefix('/{ouvrage}/composition')->name('composition.')->group(function () {
//                 Route::post('/', [AdminCompositionController::class, 'store'])->name('store');
//                 Route::put('/{composant}', [AdminCompositionController::class, 'update'])->name('update');
//                 Route::delete('/{composant}', [AdminCompositionController::class, 'destroy'])->name('destroy');
//                 Route::post('/reorder', [AdminCompositionController::class, 'reorder'])->name('reorder');
//             });

//             // Caractéristiques
//             Route::prefix('/{ouvrage}/caracteristiques')->name('caracteristiques.')->group(function () {
//                 Route::post('/', [AdminCaracteristiqueController::class, 'store'])->name('store');
//                 Route::put('/{caracteristique}', [AdminCaracteristiqueController::class, 'update'])->name('update');
//                 Route::delete('/{caracteristique}', [AdminCaracteristiqueController::class, 'destroy'])->name('destroy');
//                 Route::post('/reorder', [AdminCaracteristiqueController::class, 'reorder'])->name('reorder');
//             });

//             // Médias
//             Route::prefix('/{ouvrage}/medias')->name('medias.')->group(function () {
//                 Route::post('/upload', [AdminMediaController::class, 'store'])->name('store');
//                 Route::delete('/{media}', [AdminMediaController::class, 'destroy'])->name('destroy');
//                 Route::post('/{media}/principal', [AdminMediaController::class, 'setPrincipal'])->name('principal');
//                 Route::post('/reorder', [AdminMediaController::class, 'reorder'])->name('reorder');
//             });
//         });

//         // ============================================================
//         // COMPOSANTS (CRUD indépendant)
//         // ============================================================
//         Route::prefix('composants')->name('composants.')->group(function () {
//             Route::get('/', [ComposantController::class, 'index'])->name('index');
//             Route::get('/creer', [ComposantController::class, 'create'])->name('create');
//             Route::post('/', [ComposantController::class, 'store'])->name('store');
//             Route::get('/{composant}/editer', [ComposantController::class, 'edit'])->name('edit');
//             Route::put('/{composant}', [ComposantController::class, 'update'])->name('update');
//             Route::delete('/{composant}', [ComposantController::class, 'destroy'])->name('destroy');
//         });

//         // ============================================================
//         // GAMMES / CATÉGORIES (liste simple)
//         // ============================================================
//         Route::get('/gammes', [DashboardController::class, 'gammes'])->name('gammes.index');
//         Route::get('/categories', [DashboardController::class, 'categories'])->name('categories.index');
//     });
// });