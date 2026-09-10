<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Composant;
use App\Models\Gamme;
use App\Models\Categorie;

class DashboardController extends Controller
{
    public function index()
    {
        // Compteurs simples (pas de graphiques)
        $stats = [
            'ouvrages' => Ouvrage::count(),
            'composants' => Composant::count(),
            'gammes' => Gamme::count(),
            'categories' => Categorie::count(),
        ];

        // Derniers ouvrages modifiés
        $derniersOuvrages = Ouvrage::with('gamme')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'derniersOuvrages'));
    }

    public function gammes()
    {
        $gammes = Gamme::withCount('ouvrages')->orderBy('ordre_affichage')->get();
        return view('admin.gammes.index', compact('gammes'));
    }

    public function categories()
    {
        $categories = Categorie::withCount('ouvrages')->orderBy('nom')->get();
        return view('admin.categories.index', compact('categories'));
    }
}