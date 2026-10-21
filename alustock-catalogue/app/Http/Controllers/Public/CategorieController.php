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
        $categories = Categorie::withReferencesCount()
                               ->orderBy('nom')
                               ->get();

        $totalCategories = $categories->count();
        $totalOuvrages = $categories->sum('ouvrages_count');

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
                                     ->join('composition_ouvrage as co', 'co.ouvrage_id', '=', 'ouvrages.id')
                                     ->distinct()
                                     ->count('co.composant_id');

        return view('public.categories.show', compact('categorie', 'ouvrages', 'composantsCount'));
    }
}
