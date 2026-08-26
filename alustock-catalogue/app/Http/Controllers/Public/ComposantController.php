<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Composant;
use Illuminate\Http\Request;

class ComposantController extends Controller
{
    /**
     * Liste tous les composants
     */
    public function index(Request $request)
    {
        $query = Composant::with(['typeComposant', 'gamme', 'finitions'])
                         ->disponible();

        // Filtre par recherche
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%");
            });
        }

        // Filtre par gamme
        if ($request->has('gamme')) {
            $query->where('gamme_id', $request->gamme);
        }

        // Filtre par type de composant
        if ($request->has('type')) {
            $query->where('type_composant_id', $request->type);
        }

        $composants = $query->paginate(20);

        return view('public.composants.index', compact('composants'));
    }

    /**
     * Affiche le détail d'un composant
     */
    public function show(Composant $composant)
    {
        // Charger les relations
        $composant->load([
            'typeComposant',
            'gamme',
            'finitions' => function ($query) {
                $query->orderBy('nom');
            },
            'caracteristiques' => function ($query) {
                $query->orderBy('ordre_affichage');
            },
            'medias',
            'documents'
        ]);

        // Ouvrages qui utilisent ce composant
        $ouvrages = $composant->ouvrages()
                              ->with(['gamme', 'categorie'])
                              ->actif()
                              ->limit(6)
                              ->get();

        return view('public.composants.show', compact('composant', 'ouvrages'));
    }

    /**
     * Autocomplétion pour la recherche
     */
    public function autocomplete(Request $request)
    {
        $search = $request->get('q');

        if (empty($search)) {
            return response()->json([]);
        }

        $composants = Composant::disponible()
            ->where('designation', 'LIKE', "%{$search}%")
            ->orWhere('reference', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'designation', 'reference', 'slug']);

        return response()->json($composants);
    }
}