<?php

namespace App\Http\Controllers\Public;

use App\Support\MediaStorage;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Composant;
use App\Models\Ouvrage;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /** Page de recherche avancée. */
    public function index(Request $request)
    {
        $query     = trim((string) $request->get('q', ''));
        $type      = $request->get('type', 'all'); // all | ouvrages | composants
        $categorie = $request->get('categorie');
        $sort      = $request->get('sort', 'relevance');

        $ouvrages   = collect();
        $composants = collect();

        if ($query !== '' || $categorie) {
            $categorieId = $categorie ? Categorie::where('slug', $categorie)->value('id') : null;

            $ouvragesQuery = Ouvrage::with('categorie')
                ->actif()
                ->recherche($query)
                ->when($categorieId, fn ($q) => $q->where('categorie_id', $categorieId))
            ;

            if ($sort === 'relevance' && $query !== '') {
                $like = "%{$query}%";
                $ouvragesQuery->orderByRaw(
                    'CASE WHEN nom LIKE ? THEN 1 WHEN reference LIKE ? THEN 2 WHEN description_courte LIKE ? THEN 3 ELSE 4 END',
                    [$like, $like, $like]
                );
            } elseif ($sort === 'nom') {
                $ouvragesQuery->orderBy('nom');
            } elseif ($sort === 'created_at') {
                $ouvragesQuery->orderByDesc('created_at');
            }

            if ($type === 'all' || $type === 'composants') {
                $composants = Composant::with('typeComposant')
                    ->disponible()
                    ->recherche($query)
                    ->limit(20)
                    ->get();
            }

            $ouvrages = $ouvragesQuery->paginate(20)->withQueryString();
        }

        return view('public.search.index', [
            'ouvrages'   => $ouvrages,
            'composants' => $composants,
            'query'      => $query,
            'type'       => $type,
            'categorie'  => $categorie,
            'sort'       => $sort,
            'categories' => Categorie::ordonnees()->get(),
        ]);
    }

    /** Autocomplétion (AJAX) : ouvrages + composants. */
    public function autocomplete(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        // Les OR sont groupés dans scopeRecherche : actif()/disponible() restent appliqués.
        $ouvrages = Ouvrage::actif()->recherche($query, false)->limit(5)
            ->get(['id', 'nom', 'reference', 'slug', 'image_principale']);

        $composants = Composant::disponible()->recherche($query, false)->limit(5)
            ->get(['id', 'designation', 'reference', 'slug']);

        $results = [];

        foreach ($ouvrages as $o) {
            $results[] = [
                'type'      => 'ouvrage',
                'label'     => $o->nom,
                'reference' => $o->reference,
                'url'       => route('ouvrages.show', $o),
                'image'     => MediaStorage::url($o->image_principale),
                'badge'     => 'Ouvrage',
            ];
        }

        foreach ($composants as $c) {
            $results[] = [
                'type'      => 'composant',
                'label'     => $c->designation,
                'reference' => $c->reference,
                'url'       => route('composants.show', $c),
                'image'     => null,
                'badge'     => 'Composant',
            ];
        }

        // Références commençant par la saisie en premier (tri stable)
        usort($results, fn ($a, $b) =>
            (int) str_starts_with((string) $b['reference'], $query) <=> (int) str_starts_with((string) $a['reference'], $query)
        );

        return response()->json($results);
    }
}
