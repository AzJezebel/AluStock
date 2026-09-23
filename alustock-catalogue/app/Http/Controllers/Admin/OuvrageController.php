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
        $composantsDisponibles = Composant::with('typeComposant')
            ->disponible()
            ->orderBy('designation')
            ->get();
        $typesComposant = TypeComposant::orderBy('nom')->get();
    
        return view('admin.ouvrages.create', compact(
            'gammes', 
            'categories', 
            'composantsDisponibles', 
            'typesComposant'
        ));
    }

    /**
     * Enregistrement
     */
    public function store(StoreOuvrageRequest $request)
    {
        $validated = $request->validated();
        $validated['est_actif'] = $request->has('est_actif');

        $ouvrage = Ouvrage::create($validated);

        // ============================================================
        // 1. TRAITEMENT DE LA COMPOSITION
        // ============================================================
        $tempComposition = json_decode($request->input('temp_composition', '[]'), true);

        if (!empty($tempComposition)) {
            $ordre = 0;
            foreach ($tempComposition as $item) {
                $composantId = $item['composant_id'];

                // Si c'est un nouveau composant, on le crée d'abord
                if (empty($composantId) && !empty($item['is_new'])) {
                    $composant = Composant::create([
                        'reference' => $item['reference'],
                        'designation' => $item['designation'],
                        'type_composant_id' => $item['type_composant_id'] ?? null,
                        'matiere' => $item['matiere'] ?? null,
                        'est_disponible' => true,
                    ]);
                    $composantId = $composant->id;
                }

                if ($composantId) {
                    $ouvrage->composants()->attach($composantId, [
                        'quantite' => $item['quantite'] ?? 1,
                        'unite' => $item['unite'] ?? 'u',
                        'ordre' => ++$ordre,
                    ]);
                }
            }
        }

        // ============================================================
        // 2. TRAITEMENT DES CARACTÉRISTIQUES
        // ============================================================
        $tempCaracs = json_decode($request->input('temp_caracteristiques', '[]'), true);

        if (!empty($tempCaracs)) {
            foreach ($tempCaracs as $index => $carac) {
                if (empty($carac['cle']) || empty($carac['valeur'])) continue;

                $ouvrage->caracteristiques()->create([
                    'cle' => $carac['cle'],
                    'valeur' => $carac['valeur'],
                    'unite' => $carac['unite'] ?? null,
                    'ordre_affichage' => $index + 1,
                ]);
            }
        }

        // ============================================================
        // 3. TRAITEMENT DES MÉDIAS
        // ============================================================
        if ($request->hasFile('medias_fichiers')) {
            $this->handleMedias($ouvrage, $request);
        }

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Ouvrage créé avec succès.');
    }

    protected function handleMedias(Ouvrage $ouvrage, Request $request): void
    {
        $files = $request->file('medias_fichiers', []);
        $typeMedia = $request->input('medias_type_media', 'schema');

        if (empty($files)) return;

        $maxOrdre = 0;
        $count = 0;

        foreach ($files as $file) {
            $filename = \Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/ouvrages', $filename, 'public');

            $media = \App\Models\Media::create([
                'chemin_fichier' => $path,
                'titre' => $file->getClientOriginalName(),
                'type_media' => $typeMedia,
                'taille_octets' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'est_principal' => $count === 0,
            ]);

            $ouvrage->medias()->attach($media->id, [
                'ordre' => $maxOrdre + $count + 1,
            ]);

            $count++;
        }
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