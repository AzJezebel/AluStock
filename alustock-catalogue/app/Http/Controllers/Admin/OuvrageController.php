<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOuvrageRequest;
use App\Http\Requests\Admin\UpdateOuvrageRequest;
use App\Models\Ouvrage;
use App\Models\Gamme;
use App\Models\Categorie;
use App\Models\Composant;
use App\Models\TypeComposant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OuvrageController extends Controller
{
    /**
     * Liste des ouvrages avec tri et pagination
     */
    public function index(Request $request)
    {
        $query = Ouvrage::with(['gamme', 'categorie']);

        // Recherche
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%");
            });
        }

        // Filtres
        if ($request->filled('gamme')) {
            $query->where('gamme_id', $request->gamme);
        }
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }
        if ($request->filled('statut')) {
            $query->where('est_actif', $request->statut === 'actif');
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['reference', 'nom', 'created_at', 'updated_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $ouvrages = $query->paginate(20)->withQueryString();
        $gammes = Gamme::orderBy('ordre_affichage')->get();
        $categories = Categorie::orderBy('nom')->get();

        return view('admin.ouvrages.index', compact('ouvrages', 'gammes', 'categories', 'sort', 'direction'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $gammes = Gamme::orderBy('ordre_affichage')->get();
        $categories = Categorie::orderBy('nom')->get();

        return view('admin.ouvrages.create', compact('gammes', 'categories'));
    }

    /**
     * Enregistrement
     */
    public function store(StoreOuvrageRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->nom);
        $validated['est_actif'] = $request->has('est_actif');

        $ouvrage = Ouvrage::create($validated);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Ouvrage créé avec succès.');
    }

    /**
     * Formulaire d'édition (avec composition, caractéristiques, médias)
     */
    public function edit(Ouvrage $ouvrage)
    {
        $ouvrage->load([
            'gamme',
            'categorie',
            'composants' => fn($q) => $q->orderBy('pivot_ordre'),
            'composants.typeComposant',
            'composants.finitions',
            'caracteristiques' => fn($q) => $q->orderBy('ordre_affichage'),
            'medias' => fn($q) => $q->orderBy('media_morph.ordre'),
        ]);

        $gammes = Gamme::orderBy('ordre_affichage')->get();
        $categories = Categorie::orderBy('nom')->get();
        $composantsDisponibles = Composant::with('typeComposant')
            ->disponible()
            ->orderBy('designation')
            ->get();
        $typesComposant = TypeComposant::orderBy('nom')->get();

        return view('admin.ouvrages.edit', compact(
            'ouvrage',
            'gammes',
            'categories',
            'composantsDisponibles',
            'typesComposant'
        ));
    }

    /**
     * Mise à jour
     */
    public function update(UpdateOuvrageRequest $request, Ouvrage $ouvrage)
    {
        $validated = $request->validated();
        
        if ($request->nom !== $ouvrage->nom) {
            $validated['slug'] = Str::slug($request->nom);
        }
        
        $validated['est_actif'] = $request->has('est_actif');

        $ouvrage->update($validated);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Ouvrage mis à jour.');
    }

    /**
     * Suppression
     */
    public function destroy(Ouvrage $ouvrage)
    {
        $ouvrage->delete();

        return redirect()
            ->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage supprimé.');
    }
}