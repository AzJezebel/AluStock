<?php

use App\Http\Controllers\Public\CategorieController;
use App\Http\Controllers\Public\ComposantController;
use App\Http\Controllers\Public\GammeController;
use App\Http\Controllers\Public\OuvrageController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('catergories.index');
})->name('home');

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