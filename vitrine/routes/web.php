<?php
// routes/web.php

use App\Http\Controllers\Public\VitrineController;
use App\Http\Controllers\Public\CatalogueController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\OuvrageController;
use App\Http\Controllers\Public\GammeController;
use App\Http\Controllers\Public\CategorieController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes du site vitrine AluStock
|--------------------------------------------------------------------------
*/

// =============================================
// ROUTES PRINCIPALES DU SITE VITRINE
// =============================================

// Page d'accueil
Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');

// =============================================
// ROUTES DU CATALOGUE TECHNIQUE
// =============================================

Route::prefix('catalogue')->name('catalogue.')->group(function () {
    // Page principale du catalogue
    Route::get('/', [CatalogueController::class, 'index'])->name('index');
    
    // Recherche dans le catalogue
    Route::get('/recherche', [CatalogueController::class, 'search'])->name('search');
    
    // Filtres du catalogue
    Route::get('/filtres', [CatalogueController::class, 'filters'])->name('filters');
    
    // Détail d'un produit
    Route::get('/produit/{slug}', [CatalogueController::class, 'show'])->name('show');
    
    // Téléchargement de fiche technique
    Route::get('/produit/{id}/fiche-technique', [CatalogueController::class, 'downloadFiche'])->name('download.fiche');
});

// =============================================
// ROUTES DES OUVRAGES (réalisations)
// =============================================

Route::prefix('realisations')->name('ouvrages.')->group(function () {
    // Liste des réalisations
    Route::get('/', [OuvrageController::class, 'index'])->name('index');
    
    // Détail d'une réalisation
    Route::get('/{slug}', [OuvrageController::class, 'show'])->name('show');
    
    // Filtrer par catégorie
    Route::get('/categorie/{slug}', [OuvrageController::class, 'byCategorie'])->name('byCategorie');
    
    // Filtrer par gamme
    Route::get('/gamme/{slug}', [OuvrageController::class, 'byGamme'])->name('byGamme');
});

// =============================================
// ROUTES DES GAMMES
// =============================================

Route::prefix('gammes')->name('gammes.')->group(function () {
    // Liste des gammes
    Route::get('/', [GammeController::class, 'index'])->name('index');
    
    // Détail d'une gamme
    Route::get('/{slug}', [GammeController::class, 'show'])->name('show');
});

// =============================================
// ROUTES DES CATÉGORIES
// =============================================

Route::prefix('categories')->name('categories.')->group(function () {
    // Liste des catégories
    Route::get('/', [CategorieController::class, 'index'])->name('index');
    
    // Détail d'une catégorie
    Route::get('/{slug}', [CategorieController::class, 'show'])->name('show');
});



// // =============================================
// // ROUTES ADMIN (optionnel)
// // =============================================

// Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
//     // Dashboard
//     Route::get('/dashboard', function () {
//         return view('admin.dashboard');
//     })->name('dashboard');
    
//     // Gestion des ouvrages
//     Route::resource('ouvrages', App\Http\Controllers\Admin\OuvrageController::class);
    
//     // Gestion des gammes
//     Route::resource('gammes', App\Http\Controllers\Admin\GammeController::class);
    
//     // Gestion des catégories
//     Route::resource('categories', App\Http\Controllers\Admin\CategorieController::class);
    
//     // Gestion des produits
//     Route::resource('produits', App\Http\Controllers\Admin\ProduitController::class);
// });

// =============================================
// ROUTE DE REDIRECTION (404)
// =============================================

Route::fallback(function () {
    return view('errors.404');
});