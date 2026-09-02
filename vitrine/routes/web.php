<?php
// routes/web.php

use App\Http\Controllers\Public\VitrineController;
use App\Http\Controllers\Public\CatalogueController;

use App\Http\Controllers\Public\OuvrageController as PublicOuvrageController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\GammeController as AdminGammeController;
use App\Http\Controllers\Admin\OuvrageController as AdminOuvrageController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;



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
    Route::get('/', [PublicOuvrageController::class, 'index'])->name('index');
    
    // Détail d'une réalisation
    Route::get('/{slug}', [PublicOuvrageController::class, 'show'])->name('show');
    
    // Filtrer par catégorie
    Route::get('/categorie/{slug}', [PublicOuvrageController::class, 'byCategorie'])->name('byCategorie');
    
    // Filtrer par gamme
    Route::get('/gamme/{slug}', [PublicOuvrageController::class, 'byGamme'])->name('byGamme');
});



// // =============================================
// // ROUTES ADMIN 
// // =============================================


Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des ouvrages
    Route::resource('ouvrages', AdminOuvrageController::class)->parameters(['categories' => 'categorie']);
    Route::post('ouvrages/{ouvrage}/toggle-status', [AdminOuvrageController::class, 'toggleStatus'])->name('ouvrages.toggle-status');
    Route::post('ouvrages/{ouvrage}/toggle-featured', [AdminOuvrageController::class, 'toggleFeatured'])->name('ouvrages.toggle-featured');
    Route::delete('ouvrages/{ouvrage}/media/{media}', [AdminOuvrageController::class, 'deleteImage'])->name('ouvrages.delete-image');
    Route::post('ouvrages/{ouvrage}/reorder-images', [AdminOuvrageController::class, 'reorderImages'])->name('ouvrages.reorder-images');

    // Gestion des catégories
    Route::resource('categories', AdminCategorieController::class);
    Route::post('categories/reorder', [AdminCategorieController::class, 'reorder'])->name('categories.reorder');
    Route::post('categories/{categorie}/toggle-status', [AdminCategorieController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Gestion des gammes
    Route::resource('gammes', AdminGammeController::class);
    Route::post('gammes/reorder', [AdminGammeController::class, 'reorder'])->name('gammes.reorder');
    Route::post('gammes/{gamme}/toggle-status', [AdminGammeController::class, 'toggleStatus'])->name('gammes.toggle-status');

    // Gestion des médias
    Route::get('medias', [AdminMediaController::class, 'index'])->name('medias.index');
    Route::post('medias/upload', [AdminMediaController::class, 'upload'])->name('medias.upload');
    Route::delete('medias/{media}', [AdminMediaController::class, 'destroy'])->name('medias.destroy');
    Route::post('medias/bulk-delete', [AdminMediaController::class, 'bulkDestroy'])->name('medias.bulk-destroy');
    Route::post('medias/{media}/set-primary', [AdminMediaController::class, 'setPrimary'])->name('medias.set-primary');

    // Paramètres du site
    Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/{key}', [AdminSettingsController::class, 'get'])->name('settings.get');
});


// =============================================
// ROUTE DE REDIRECTION (404)
// =============================================

// Route::fallback(function () {
//     return view('errors.404');
// });



// =============================================
// ROUTES ADMIN
// =============================================

// =============================================
// ROUTES DE LOGIN (Simples)
// =============================================

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        return redirect()->route('admin.dashboard');
    }
    
    return back()->withErrors([
        'email' => 'Identifiants incorrects.',
    ]);
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');