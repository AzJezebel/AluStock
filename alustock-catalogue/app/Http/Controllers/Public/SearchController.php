<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Composant;
use App\Models\Categorie;
use App\Models\Gamme;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Page de recherche avancée
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, ouvrages, composants
        $categorie = $request->get('categorie');
        $gamme = $request->get('gamme');
        $sort = $request->get('sort', 'relevance');

        // Résultats par défaut
        $ouvrages = collect();
        $composants = collect();

        if (!empty($query) || $categorie || $gamme) {
            // 1. Recherche des ouvrages
            $ouvragesQuery = Ouvrage::with(['gamme', 'categorie'])
                ->actif();

            if (!empty($query)) {
                $ouvragesQuery->where(function ($q) use ($query) {
                    $q->where('nom', 'LIKE', "%{$query}%")
                      ->orWhere('reference', 'LIKE', "%{$query}%")
                      ->orWhere('description_courte', 'LIKE', "%{$query}%")
                      ->orWhere('description_technique', 'LIKE', "%{$query}%");
                });
            }

            if ($categorie) {
                $categorieModel = Categorie::where('slug', $categorie)->first();
                if ($categorieModel) {
                    $ouvragesQuery->where('categorie_id', $categorieModel->id);
                }
            }

            if ($gamme) {
                $gammeModel = Gamme::where('slug', $gamme)->first();
                if ($gammeModel) {
                    $ouvragesQuery->where('gamme_id', $gammeModel->id);
                }
            }

            // Tri
            if ($sort === 'relevance' && !empty($query)) {
                $ouvragesQuery->orderByRaw(
                    "CASE 
                        WHEN nom LIKE ? THEN 1 
                        WHEN reference LIKE ? THEN 2 
                        WHEN description_courte LIKE ? THEN 3 
                        ELSE 4 
                    END",
                    ["%{$query}%", "%{$query}%", "%{$query}%"]
                );
            } elseif ($sort === 'nom') {
                $ouvragesQuery->orderBy('nom');
            } elseif ($sort === 'created_at') {
                $ouvragesQuery->orderBy('created_at', 'desc');
            }

            // 2. Recherche des composants (si type = all ou composants)
            if ($type === 'all' || $type === 'composants') {
                $composantsQuery = Composant::with(['typeComposant', 'gamme'])
                    ->disponible();

                if (!empty($query)) {
                    $composantsQuery->where(function ($q) use ($query) {
                        $q->where('designation', 'LIKE', "%{$query}%")
                          ->orWhere('reference', 'LIKE', "%{$query}%")
                          ->orWhere('matiere', 'LIKE', "%{$query}%");
                    });
                }

                if ($gamme) {
                    $gammeModel = Gamme::where('slug', $gamme)->first();
                    if ($gammeModel) {
                        $composantsQuery->where('gamme_id', $gammeModel->id);
                    }
                }

                $composants = $composantsQuery->limit(20)->get();
            }

            // Pagination des ouvrages
            $ouvrages = $ouvragesQuery->paginate(20);
        }

        // Récupérer les filtres disponibles
        $categories = Categorie::orderBy('nom')->get();
        $gammes = Gamme::orderBy('ordre_affichage')->get();

        return view('public.search.index', compact(
            'ouvrages',
            'composants',
            'query',
            'type',
            'categorie',
            'gamme',
            'sort',
            'categories',
            'gammes'
        ));
    }

    /**
     * Autocomplétion pour la barre de recherche (AJAX)
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Recherche dans les ouvrages
        $ouvrages = Ouvrage::actif()
            ->where('nom', 'LIKE', "%{$query}%")
            ->orWhere('reference', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'nom', 'reference', 'slug', 'image_principale']);

        // Recherche dans les composants
        $composants = Composant::disponible()
            ->where('designation', 'LIKE', "%{$query}%")
            ->orWhere('reference', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get(['id', 'designation', 'reference', 'slug']);

        // Formatage des résultats
        $results = [];

        foreach ($ouvrages as $ouvrage) {
            $results[] = [
                'type' => 'ouvrage',
                'label' => $ouvrage->nom,
                'reference' => $ouvrage->reference,
                'url' => route('ouvrages.show', $ouvrage->slug),
                'image' => $ouvrage->image_principale ? asset('storage/' . $ouvrage->image_principale) : null,
                'badge' => 'Ouvrage',
            ];
        }

        foreach ($composants as $composant) {
            $results[] = [
                'type' => 'composant',
                'label' => $composant->designation,
                'reference' => $composant->reference,
                'url' => route('composants.show', $composant->slug),
                'image' => null,
                'badge' => 'Composant',
            ];
        }

        // Trier par pertinence (priorité aux références exactes)
        usort($results, function ($a, $b) use ($query) {
            $aExact = strpos($a['reference'], $query) === 0;
            $bExact = strpos($b['reference'], $query) === 0;
            if ($aExact && !$bExact) return -1;
            if (!$aExact && $bExact) return 1;
            return 0;
        });

        return response()->json($results);
    }

    /**
     * Recherche rapide (redirect vers la page de recherche)
     */
    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        return redirect()->route('search.index', ['q' => $query]);
    }
}