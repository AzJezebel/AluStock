<?php
// app/Http/Controllers/Public/VitrineController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Ouvrage;

class VitrineController extends Controller
{
    /**
     * Page d'accueil du site vitrine.
     */
    public function index()
    {
        // 6 réalisations affichées : les ouvrages « en vedette » d'abord, puis les plus récents.
        // Les images sont chargées d'avance (mainImage + gallery) pour éviter une requête par ouvrage.
        $featuredOuvrages = Ouvrage::with(['categorie', 'mainImage', 'gallery'])
            ->actif()
            ->orderByDesc('is_featured')
            ->latest()
            ->limit(6)
            ->get();

        $stats = [
            'categories' => Categorie::count(),
            'ouvrages'   => Ouvrage::actif()->count(),
        ];

        // Alimente le bandeau défilant
        $categories = Categorie::orderBy('nom')->limit(8)->get();

        return view('public.vitrine.index', compact('featuredOuvrages', 'stats', 'categories'));
    }
}