<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Gamme;
use App\Models\Categorie;
use App\Models\Ouvrage;
use App\Models\Composant;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    // Accueil
    public function home()
    {
        $gammes = Gamme::orderBy('ordre_affichage')->get();
        $ouvrages = Ouvrage::actif()->latest()->take(6)->get();
        return view('public.home', compact('gammes', 'ouvrages'));
    }

    // Gammes
    public function gammesIndex()
    {
        $gammes = Gamme::orderBy('ordre_affichage')->paginate(20);
        return view('public.gammes.index', compact('gammes'));
    }

    public function gammeShow(Gamme $gamme)
    {
        $ouvrages = $gamme->ouvrages()->actif()->paginate(20);
        return view('public.gammes.show', compact('gamme', 'ouvrages'));
    }

    public function ouvragesByGamme(Gamme $gamme)
    {
        $ouvrages = $gamme->ouvrages()->actif()->paginate(20);
        return view('public.ouvrages.index', compact('ouvrages', 'gamme'));
    }

    // Catégories
    public function categoriesIndex()
    {
        $categories = Categorie::all();
        return view('public.categories.index', compact('categories'));
    }

    public function categorieShow(Categorie $categorie)
    {
        $ouvrages = $categorie->ouvrages()->actif()->paginate(20);
        return view('public.categories.show', compact('categorie', 'ouvrages'));
    }

    public function ouvragesByCategorie(Categorie $categorie)
    {
        $ouvrages = $categorie->ouvrages()->actif()->paginate(20);
        return view('public.ouvrages.index', compact('ouvrages', 'categorie'));
    }

    // Ouvrages
    public function ouvragesIndex()
    {
        $ouvrages = Ouvrage::actif()->paginate(20);
        return view('public.ouvrages.index', compact('ouvrages'));
    }

    public function ouvrageShow(Ouvrage $ouvrage)
    {
        $ouvrage->load(['gamme', 'categorie', 'composants' => function($q) {
            $q->orderBy('pivot_ordre');
        }]);
        return view('public.ouvrages.show', compact('ouvrage'));
    }

    public function ouvrageComposition(Ouvrage $ouvrage)
    {
        $composition = $ouvrage->composants()->orderBy('pivot_ordre')->get();
        return view('public.ouvrages.composition', compact('ouvrage', 'composition'));
    }

    public function ouvragePrint(Ouvrage $ouvrage)
    {
        return view('public.ouvrages.print', compact('ouvrage'));
    }

    // Composants
    public function composantsIndex()
    {
        $composants = Composant::disponible()->paginate(20);
        return view('public.composants.index', compact('composants'));
    }

    public function composantShow(Composant $composant)
    {
        $composant->load(['typeComposant', 'gamme', 'finitions', 'caracteristiques']);
        return view('public.composants.show', compact('composant'));
    }
}