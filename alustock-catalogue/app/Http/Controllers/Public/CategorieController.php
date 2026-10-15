<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Liste toutes les catégories avec statistiques
     */
    public function index()
    {
        $categories = Categorie::withCount('ouvrages')
                               ->orderBy('nom')
                               ->get();

        $totalCategories = Categorie::count();
        $totalOuvrages = Categorie::withCount('ouvrages')->get()->sum('ouvrages_count');

        return view('public.categories.index', compact('categories', 'totalCategories', 'totalOuvrages'));
    }

    /**
     * Affiche le détail d'une catégorie avec ses ouvrages
     */
    public function show(Categorie $categorie)
    {
        $ouvrages = $categorie->ouvrages()
                              ->with('categorie')
                              ->actif()
                              ->paginate(12);

        // Comptage des composants associés à la catégorie
        $composantsCount = $categorie->ouvrages()
                                     ->withCount('composants')
                                     ->get()
                                     ->sum('composants_count');

        return view('public.categories.show', compact('categorie', 'ouvrages', 'composantsCount'));
    }
}
