<?php

namespace App\Providers;

use App\Http\ViewComposers\SidebarComposer;
use App\Models\Categorie;
use App\Models\Composant;
use App\Models\Ouvrage;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Alias stockés en base (media_morph.mediable_type, caracteristiques.caracterisable_type)
        // à la place du nom de classe PHP. enforceMorphMap : une entité polymorphe non déclarée ici
        // lève une erreur au lieu d'écrire silencieusement le nom de classe.
        Relation::enforceMorphMap([
            'ouvrage'   => Ouvrage::class,
            'composant' => Composant::class,
            'categorie' => Categorie::class,
        ]);

        View::composer('public.partials.sidebar-menu', SidebarComposer::class);
    }
}
