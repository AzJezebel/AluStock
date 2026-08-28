<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Gamme;

class HomeController extends Controller
{
    public function index()
    {
        // Catégories en vedette (4 premières)
        $featuredCategories = Categorie::withCount('ouvrages')
                                       ->orderBy('ouvrages_count', 'desc')
                                       ->limit(4)
                                       ->get();

        // Gammes en vedette (4 premières)
        $featuredGammes = Gamme::withCount('ouvrages')
                               ->orderBy('ordre_affichage')
                               ->limit(4)
                               ->get();

        // Statistiques globales
        $totalCategories = Categorie::count();
        $totalGammes = Gamme::count();

        return view('public.home', compact(
            'featuredCategories',
            'featuredGammes',
            'totalCategories',
            'totalGammes'
        ));
    }
}