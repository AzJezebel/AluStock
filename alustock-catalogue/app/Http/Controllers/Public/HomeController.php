<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gamme;
use App\Models\Categorie;
use App\Models\Ouvrage;
use App\Models\Composant;

class HomeController extends Controller
{
    public function index()
    {
        // Catégories en vedette
        $featuredCategories = Categorie::withCount('ouvrages')
                                       ->orderBy('ouvrages_count', 'desc')
                                       ->limit(4)
                                       ->get();

        // Gammes en vedette
        $featuredGammes = Gamme::withCount('ouvrages')
                               ->orderBy('ordre_affichage')
                               ->limit(4)
                               ->get();

        // ============================================================
        // STATISTIQUES RÉELLES
        // ============================================================
        $stats = [
            'references' => Composant::count() + Ouvrage::count(),  
            'ouvrages' => Ouvrage::count(),           
            'categories' => Categorie::count(),      
            'gammes' => Gamme::count(),               
        ];

        return view('public.home', compact(
            'featuredCategories',
            'featuredGammes',
            'stats'
        ));
    }
}