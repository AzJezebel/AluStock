<?php

namespace App\Http\ViewComposers;

use App\Models\Categorie;
use Illuminate\View\View;

/**
 * Alimente public/partials/sidebar-menu.blade.php (enregistré dans AppServiceProvider).
 * La requête n'est plus faite dans la vue.
 */
class SidebarComposer
{
    public function compose(View $view): void
    {
        $view->with('sidebarCategories', Categorie::withCount('ouvrages')->ordonnees()->get());
    }
}
