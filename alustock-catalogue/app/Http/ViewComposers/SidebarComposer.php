<?php

namespace App\Http\ViewComposers;

use App\Models\Categorie;
use App\Models\Gamme;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view)
    {
        $categories = Categorie::withCount('ouvrages')
                               ->orderBy('nom')
                               ->get();
        
        $gammes = Gamme::withCount('ouvrages')
                       ->orderBy('ordre_affichage')
                       ->get();

        $view->with([
            'sidebarCategories' => $categories,
            'sidebarGammes' => $gammes,
        ]);
    }
}