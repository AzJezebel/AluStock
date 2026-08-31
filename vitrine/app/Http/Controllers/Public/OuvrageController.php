<?php
// app/Http/Controllers/Public/OuvrageController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Gamme;
use Illuminate\Http\Request;

class OuvrageController extends Controller
{
    public function index(Request $request)
    {
        $query = Ouvrage::with(['categorie', 'gamme'])->actif();

        if ($request->has('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->has('gamme')) {
            $query->where('gamme_id', $request->gamme);
        }

        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        $ouvrages = $query->latest()->paginate(9);
        $categories = Categorie::actif()->get();
        $gammes = Gamme::actif()->get();

        return view('public.ouvrages.index', compact('ouvrages', 'categories', 'gammes'));
    }

    public function show($slug)
    {
        $ouvrage = Ouvrage::with(['categorie', 'gamme'])
            ->where('slug', $slug)
            ->actif()
            ->firstOrFail();

        // Incrémenter le compteur de vues
        $ouvrage->increment('views');

        $ouvragesSimilaires = Ouvrage::where('categorie_id', $ouvrage->categorie_id)
            ->where('id', '!=', $ouvrage->id)
            ->actif()
            ->limit(3)
            ->get();

        return view('public.ouvrages.show', compact('ouvrage', 'ouvragesSimilaires'));
    }

    public function byCategorie($slug)
    {
        $categorie = Categorie::where('slug', $slug)->firstOrFail();
        $ouvrages = Ouvrage::with(['categorie', 'gamme'])
            ->where('categorie_id', $categorie->id)
            ->actif()
            ->latest()
            ->paginate(9);

        return view('public.ouvrages.by-categorie', compact('ouvrages', 'categorie'));
    }

    public function byGamme($slug)
    {
        $gamme = Gamme::where('slug', $slug)->firstOrFail();
        $ouvrages = Ouvrage::with(['categorie', 'gamme'])
            ->where('gamme_id', $gamme->id)
            ->actif()
            ->latest()
            ->paginate(9);

        return view('public.ouvrages.by-gamme', compact('ouvrages', 'gamme'));
    }
}