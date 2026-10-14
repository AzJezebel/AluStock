<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Déclarer des services dans le conteneur (rien à faire ici pour l'instant).
     */
    public function register(): void
    {
        //
    }

    /**
     * Exécuté une fois toute l'application chargée : réglages globaux.
     */
    public function boot(): void
    {
        // Variables disponibles dans TOUTES les vues Blade, sans les passer depuis un contrôleur :
        // La valeur vient de config/vitrine.php (donc du .env) : un seul endroit à modifier.
        View::share('brand', config('vitrine.brand'));
        View::share('brandName', trim(config('vitrine.brand.first') . config('vitrine.brand.second')));
    }
}