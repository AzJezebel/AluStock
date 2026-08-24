<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gamme;
use Illuminate\Http\Request;

class GammeController extends Controller
{
    /**
     * Liste toutes les gammes avec pagination
     */
    public function index()
    {
        $gammes = Gamme::withCount('ouvrages')
                       ->orderBy('ordre_affichage')
                       ->paginate(12);

        // Statistiques pour l'en-tête
        $totalGammes = Gamme::count();
        $totalReferences = Gamme::withCount('ouvrages')->get()->sum('ouvrages_count');

        return view('public.gammes.index', compact('gammes', 'totalGammes', 'totalReferences'));
    }

    /**
     * Affiche le détail d'une gamme avec ses ouvrages
     */
    public function show(Gamme $gamme)
    {
        $ouvrages = $gamme->ouvrages()
                          ->with(['categorie', 'gamme'])
                          ->actif()
                          ->paginate(12);

        $composantsCount = $gamme->composants()->count();

        return view('public.gammes.show', compact('gamme', 'ouvrages', 'composantsCount'));
    }

    /**
     * Liste tous les ouvrages d'une gamme spécifique
     */
    public function ouvrages(Gamme $gamme)
    {
        $ouvrages = $gamme->ouvrages()
                          ->with(['categorie', 'gamme'])
                          ->actif()
                          ->paginate(20);

        return view('public.gammes.ouvrages', compact('gamme', 'ouvrages'));
    }

    /**
     * Liste tous les composants d'une gamme spécifique
     */
    public function composants(Gamme $gamme)
    {
        $composants = $gamme->composants()
                            ->with(['typeComposant', 'finitions'])
                            ->disponible()
                            ->paginate(20);

        return view('public.gammes.composants', compact('gamme', 'composants'));
    }
}