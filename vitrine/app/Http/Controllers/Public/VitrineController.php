<?php
// app/Http/Controllers/Public/VitrineController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Gamme;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class VitrineController extends Controller
{
    /**
     * Affiche la page d'accueil du site vitrine
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupération des ouvrages en vedette (6 derniers actifs)
        $featuredOuvrages = Ouvrage::with(['gamme', 'categorie'])
            ->actif()
            ->latest()
            ->limit(6)
            ->get();

        // Statistiques
        $stats = [
            'references' => 18910,
            'categories' => Categorie::count(),
            'gammes' => Gamme::count(),
            'ouvrages' => Ouvrage::actif()->count(),
        ];

        // Gammes principales pour la section
        $gammes = Gamme::whereIn('nom', ['Gamme 45', 'Gamme 55', 'Gamme Structure', 'Gamme Design'])
            ->orWhere('is_active', true)
            ->limit(4)
            ->get();

        // Catégories principales
        $categories = Categorie::whereIn('nom', ['Fenêtre', 'Porte', 'Véranda', 'Verrière', 'Garde-corps'])
            ->limit(4)
            ->get();

        return view('public.vitrine.index', compact(
            'featuredOuvrages',
            'stats',
            'gammes',
            'categories'
        ));
    }
}