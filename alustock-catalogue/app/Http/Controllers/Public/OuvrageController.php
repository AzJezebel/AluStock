<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class OuvrageController extends Controller
{
    /** Liste des ouvrages avec filtres. */
    public function index(Request $request)
    {
        $categorieCourante = $request->filled('categorie')
            ? Categorie::where('slug', $request->categorie)->first() : null;

        $sort      = in_array($request->get('sort'), ['nom', 'reference', 'created_at', 'updated_at'], true)
            ? $request->get('sort') : 'created_at';
        $direction = $request->get('direction') === 'asc' ? 'asc' : 'desc';

        $ouvrages = Ouvrage::with(['categorie', 'medias'])
            ->actif()
            ->recherche($request->get('q'))
            ->when($categorieCourante, fn ($q) => $q->where('categorie_id', $categorieCourante->id))
            ->orderBy($sort, $direction)
            ->paginate(20)
            ->withQueryString();

        return view('public.ouvrages.index', compact('ouvrages', 'categorieCourante'));
    }

    /** Détail d'un ouvrage. */
    public function show(Ouvrage $ouvrage)
    {
        // Les ordres (pivot, ordre_affichage, ordre) sont déjà portés par les relations du modèle.
        $ouvrage->load([
            'categorie', 'composants',
            'composants.finitions', 'composants.typeComposant', 'composants.medias',
            'caracteristiques', 'medias', 'documents',
        ]);

        $composantsCount = $ouvrage->composants->count();

        $similaires = Ouvrage::with('categorie')
            ->actif()
            ->where('id', '!=', $ouvrage->id)
            ->where('categorie_id', $ouvrage->categorie_id)
            ->limit(6)
            ->get();

        return view('public.ouvrages.show', compact('ouvrage', 'composantsCount', 'similaires'));
    }

    /** Composition détaillée (le poids estimé est calculé dans la vue). */
    public function composition(Ouvrage $ouvrage)
    {
        $composition = $ouvrage->composants()
            ->with([
                'typeComposant',
                'finitions' => fn ($q) => $q->where('est_par_defaut', true),
                'caracteristiques',
            ])
            ->get();

        return view('public.ouvrages.composition', compact('ouvrage', 'composition'));
    }

    /** Fiche imprimable. */
    public function print(Ouvrage $ouvrage)
    {
        $ouvrage->load(['categorie', 'composants', 'caracteristiques']);

        return view('public.ouvrages.print', compact('ouvrage'));
    }
}
