<?php
// routes/web.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\OuvrageController as PublicOuvrageController;
use App\Http\Controllers\Public\VitrineController;

use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OuvrageController as AdminOuvrageController;
use App\Http\Controllers\Admin\SiteImageController as AdminSiteImageController;

use Illuminate\Support\Facades\Route;

// =============================================
// SITE PUBLIC
// =============================================

Route::get('/', [VitrineController::class, 'index'])->name('vitrine.index');

// Réalisations (ouvrages) — les vues public.ouvrages.* restent à créer
Route::prefix('realisations')->name('ouvrages.')->controller(PublicOuvrageController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/categorie/{slug}', 'byCategorie')->name('byCategorie');
    Route::get('/{slug}', 'show')->name('show');
});

// Contact (le formulaire est dans la page d'accueil, ancre #contact)
Route::get('/contact', fn () => redirect()->to(route('vitrine.index') . '#contact'))->name('contact.show');
Route::post('/contact', [ContactController::class, 'send'])
    ->middleware('throttle:' . config('contact.throttle_per_minute', 3) . ',1')
    ->name('contact.send');

// =============================================
// AUTHENTIFICATION
// =============================================

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->middleware('throttle:5,1')->name('login.attempt');
    Route::post('/logout', 'logout')->name('logout');
});

// =============================================
// ADMIN (connecté + rôle admin / super_admin)
// =============================================

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Ouvrages
    Route::resource('ouvrages', AdminOuvrageController::class);
    Route::controller(AdminOuvrageController::class)->prefix('ouvrages/{ouvrage}')->name('ouvrages.')->group(function () {
        Route::post('toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::delete('media/{media}', 'deleteImage')->name('delete-image');
        Route::post('reorder-images', 'reorderImages')->name('reorder-images');
    });

    // Catégories
    Route::post('categories/reorder', [AdminCategorieController::class, 'reorder'])->name('categories.reorder');
    Route::post('categories/{categorie}/toggle-status', [AdminCategorieController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::resource('categories', AdminCategorieController::class)
        ->parameters(['categories' => 'categorie'])
        ->except('show');

    // Images du site (hero + « À propos »), stockées dans un JSON
    Route::prefix('site-images')->name('site-images.')->controller(AdminSiteImageController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('{slot}', 'store')->name('store');
        Route::delete('{slot}', 'destroy')->name('destroy');
        Route::post('{slot}/ordre', 'reorder')->name('reorder');
    });
});