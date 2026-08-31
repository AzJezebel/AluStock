<?php
// app/Http/Controllers/Public/CatalogueController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\Gamme;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::with(['categorie', 'gamme'])->actif();

        // Filtres
        if ($request->has('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->has('gamme')) {
            $query->where('gamme_id', $request->gamme);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('reference', 'LIKE', '%' . $request->search . '%');
            });
        }

        $produits = $query->paginate(12);
        $categories = Categorie::actif()->get();
        $gammes = Gamme::actif()->get();

        return view('public.catalogue.index', compact('produits', 'categories', 'gammes'));
    }

    public function show($slug)
    {
        $produit = Produit::with(['categorie', 'gamme'])
            ->where('slug', $slug)
            ->actif()
            ->firstOrFail();

        $produitsSimilaires = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->actif()
            ->limit(4)
            ->get();

        return view('public.catalogue.show', compact('produit', 'produitsSimilaires'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $produits = Produit::where('nom', 'LIKE', '%' . $query . '%')
            ->orWhere('reference', 'LIKE', '%' . $query . '%')
            ->actif()
            ->paginate(12);

        return view('public.catalogue.search', compact('produits', 'query'));
    }

    public function downloadFiche($id)
    {
        $produit = Produit::findOrFail($id);
        // Logique de téléchargement de la fiche technique
        // return response()->download(storage_path('app/public/fiches/' . $produit->fiche_technique));
    }
}