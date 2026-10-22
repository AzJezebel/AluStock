<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Public as Site;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes web — AluStock (catalogue)
|--------------------------------------------------------------------------
|
|   PUBLIC  lecture seule, résolution par slug.
|   ADMIN   préfixe /admin, noms admin.*, protégé par le middleware admin.auth
|           (sauf login / logout). CRUD simples via Route::resource,
|           sous-ressources (composition, caractéristiques, médias) en groupes.
|
| Contrôleurs : Site\* = App\Http\Controllers\Public\*
|               Admin\* = App\Http\Controllers\Admin\*
*/

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [Site\HomeController::class, 'index'])->name('home');

// Catégories
Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [Site\CategorieController::class, 'index'])->name('index');
    Route::get('/{categorie:slug}', [Site\CategorieController::class, 'show'])->name('show');
});

// Ouvrages
Route::prefix('ouvrages')->name('ouvrages.')->group(function () {
    Route::get('/', [Site\OuvrageController::class, 'index'])->name('index');
    Route::get('/{ouvrage:slug}', [Site\OuvrageController::class, 'show'])->name('show');
    Route::get('/{ouvrage:slug}/composition', [Site\OuvrageController::class, 'composition'])->name('composition');
    Route::get('/{ouvrage:slug}/print', [Site\OuvrageController::class, 'print'])->name('print');
});

// Composants
Route::prefix('composants')->name('composants.')->group(function () {
    Route::get('/', [Site\ComposantController::class, 'index'])->name('index');
    Route::get('/{composant:slug}', [Site\ComposantController::class, 'show'])->name('show');
});

// Recherche
Route::prefix('search')->name('search.')->group(function () {
    Route::get('/', [Site\SearchController::class, 'index'])->name('index');
    Route::get('/autocomplete', [Site\SearchController::class, 'autocomplete'])->name('autocomplete');
});

// Pages légales
Route::get('/mentions-legales', [Site\LegalController::class, 'mentions'])->name('legal.mentions');
Route::get('/confidentialite', [Site\LegalController::class, 'confidentialite'])->name('legal.confidentialite');

// Contact
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [Site\ContactController::class, 'show'])->name('show');
    Route::post('/', [Site\ContactController::class, 'send'])
        ->middleware('throttle:' . config('contact.throttle_per_minute', 3) . ',1')
        ->name('send');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (non protégé)
    Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');

    // Protégé par le middleware admin
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Ouvrages (CRUD + composition + caractéristiques)
        Route::resource('ouvrages', Admin\OuvrageController::class)
            ->parameters(['ouvrages' => 'ouvrage:slug'])
            ->except(['show']);

        Route::prefix('ouvrages/{ouvrage:slug}')->name('ouvrages.')->group(function () {

            Route::prefix('composition')->name('composition.')->group(function () {
                Route::post('/', [Admin\CompositionController::class, 'store'])->name('store');
                Route::post('/nouveau', [Admin\CompositionController::class, 'storeNew'])->name('store-new');
                Route::post('/reorder', [Admin\CompositionController::class, 'reorder'])->name('reorder');
                Route::put('/{composant}', [Admin\CompositionController::class, 'update'])->name('update');
                Route::delete('/{composant}', [Admin\CompositionController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('caracteristiques')->name('caracteristiques.')->group(function () {
                Route::post('/', [Admin\CaracteristiqueController::class, 'store'])->name('store');
                Route::post('/reorder', [Admin\CaracteristiqueController::class, 'reorder'])->name('reorder');
                Route::put('/{caracteristique}', [Admin\CaracteristiqueController::class, 'update'])->name('update');
                Route::delete('/{caracteristique}', [Admin\CaracteristiqueController::class, 'destroy'])->name('destroy');
            });
        });

        // Composants
        Route::resource('composants', Admin\ComposantController::class)
            ->except(['show']);

        // Catégories
        Route::resource('categories', Admin\CategorieController::class)
            ->parameters(['categories' => 'categorie'])
            ->except(['show']);

        // Types de composant
        Route::resource('types-composant', Admin\TypeComposantController::class)
            ->parameters(['types-composant' => 'typeComposant'])
            ->except(['show']);

        // Médias
        Route::prefix('medias')->name('medias.')->group(function () {
            Route::post('/ouvrages/{ouvrage:slug}/upload', [Admin\MediaController::class, 'storeForOuvrage'])->name('ouvrages.upload');
            Route::post('/composants/{composant:slug}/upload', [Admin\MediaController::class, 'storeForComposant'])->name('composants.upload');
            Route::put('/{media}', [Admin\MediaController::class, 'update'])->name('update');
            Route::delete('/{media}', [Admin\MediaController::class, 'destroy'])->name('destroy');
            Route::post('/{media}/principal', [Admin\MediaController::class, 'setPrincipal'])->name('principal');
        });
    });
});