<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOuvrageRequest;
use App\Http\Requests\Admin\UpdateOuvrageRequest;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Composant;
use App\Models\TypeComposant;
use App\Http\Controllers\Admin\Concerns\GereUploadsMedias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OuvrageController extends Controller
{
    use GereUploadsMedias;

    /**
     * Liste des ouvrages avec tri et pagination
     */
    public function index(Request $request)
    {
        $query = Ouvrage::with('categorie');

        // Recherche
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%");
            });
        }

        // Filtres
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
        $categories = Categorie::ordonnees()->get();

        return view('admin.ouvrages.index', compact('ouvrages', 'categories', 'sort', 'direction'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = Categorie::ordonnees()->get();
        $composantsDisponibles = Composant::with('typeComposant')
            ->disponible()
            ->orderBy('designation')
            ->get();
        $typesComposant = TypeComposant::orderBy('nom')->get();
    
        return view('admin.ouvrages.create', compact(
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

        // Tout ou rien : ouvrage, composition, caractéristiques et médias (fichiers nettoyés si échec)
        $ouvrage = $this->transactionAvecFichiers(function () use ($request, $validated) {
            $ouvrage = Ouvrage::create($validated);

            $this->attacherComposition($ouvrage, json_decode($request->input('temp_composition', '[]'), true) ?: []);
            $this->creerCaracteristiques($ouvrage, json_decode($request->input('temp_caracteristiques', '[]'), true) ?: []);

            $this->enregistrerMedias(
                $ouvrage,
                $request->file('medias_fichiers', []),
                'images/ouvrages',
                $request->input('medias_type_media', 'schema')
            );

            return $ouvrage;
        });

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Ouvrage créé avec succès.');
    }

    /** Lignes de composition saisies dans le formulaire (crée les composants "nouveaux" au passage). */
    protected function attacherComposition(Ouvrage $ouvrage, array $lignes): void
    {
        $ordre = 0;

        foreach ($lignes as $item) {
            $composantId = $item['composant_id'] ?? null;

            if (empty($composantId) && !empty($item['is_new'])) {
                // Référence déjà prise : on annule tout (transaction) avec un message clair
                $reference = mb_strtoupper(trim(preg_replace('/\s+/', ' ', (string) ($item['reference'] ?? ''))));
                if ($reference === '' || Composant::where('reference', $reference)->exists()) {
                    throw ValidationException::withMessages([
                        'temp_composition' => "La référence de composant « {$reference} » est vide ou existe déjà.",
                    ]);
                }

                $composantId = Composant::create([
                    'reference' => $item['reference'],
                    'designation' => $item['designation'],
                    'type_composant_id' => $item['type_composant_id'] ?? null,
                    'matiere' => $item['matiere'] ?? null,
                    'est_disponible' => true,
                ])->id;
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

    protected function creerCaracteristiques(Ouvrage $ouvrage, array $caracs): void
    {
        foreach ($caracs as $index => $carac) {
            if (empty($carac['cle']) || empty($carac['valeur'])) continue;

            $ouvrage->caracteristiques()->create([
                'cle' => $carac['cle'],
                'valeur' => $carac['valeur'],
                'unite' => $carac['unite'] ?? null,
                'ordre_affichage' => $index + 1,
            ]);
        }
    }

    /**
     * Formulaire d'édition (avec composition, caractéristiques, médias)
     */
    public function edit(Ouvrage $ouvrage)
    {
        $ouvrage->load([
            'categorie',
            'composants' => fn($q) => $q->orderBy('composition_ouvrage.ordre'),
            'composants.typeComposant',
            'composants.finitions',
            'caracteristiques' => fn($q) => $q->orderBy('ordre_affichage'),
            'medias' => fn($q) => $q->orderBy('media_morph.ordre'),
        ]);

        $categories = Categorie::ordonnees()->get();
        $composantsDisponibles = Composant::with('typeComposant')
            ->disponible()
            ->orderBy('designation')
            ->get();
        $typesComposant = TypeComposant::orderBy('nom')->get();

        return view('admin.ouvrages.edit', compact(
            'ouvrage',
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
        $this->supprimerAvecMedias($ouvrage);

        return redirect()
            ->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage supprimé.');
    }
}