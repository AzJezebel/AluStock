<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Gamme;
use Illuminate\Http\Request;

class OuvrageController extends Controller
{
    /**
     * Liste tous les ouvrages avec filtres optionnels
     */
    public function index(Request $request)
    {
        $query = Ouvrage::with(['gamme', 'categorie'])->actif();

        // Filtre par catégorie
        $categorieCourante = null;
        if ($request->has('categorie')) {
            $categorieCourante = Categorie::where('slug', $request->categorie)->first();
            if ($categorieCourante) {
                $query->where('categorie_id', $categorieCourante->id);
            }
        }

        // Filtre par gamme
        $gammeCourante = null;
        if ($request->has('gamme')) {
            $gammeCourante = Gamme::where('slug', $request->gamme)->first();
            if ($gammeCourante) {
                $query->where('gamme_id', $gammeCourante->id);
            }
        }

        // Filtre par recherche textuelle
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%")
                  ->orWhere('description_courte', 'LIKE', "%{$search}%")
                  ->orWhere('description_technique', 'LIKE', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['nom', 'reference', 'created_at', 'updated_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $ouvrages = $query->paginate(20);

        return view('public.ouvrages.index', compact(
            'ouvrages',
            'categorieCourante',
            'gammeCourante'
        ));
    }

    /**
     * Affiche le détail d'un ouvrage
     */
    public function show(Ouvrage $ouvrage)
    {
        // Charger les relations nécessaires
        $ouvrage->load([
            'gamme',
            'categorie',
            'composants' => function ($query) {
                $query->orderBy('pivot_ordre');
            },
            'composants.finitions',
            'composants.typeComposant',
            'caracteristiques' => function ($query) {
                $query->orderBy('ordre_affichage');
            },
            'medias' => function ($query) {
                $query->orderBy('ordre');
            },
            'documents'
        ]);

        // Comptage des composants
        $composantsCount = $ouvrage->composants->count();

        // Ouvrages similaires (même catégorie ou même gamme)
        $similaires = Ouvrage::with(['gamme', 'categorie'])
            ->actif()
            ->where('id', '!=', $ouvrage->id)
            ->where(function ($query) use ($ouvrage) {
                $query->where('categorie_id', $ouvrage->categorie_id)
                      ->orWhere('gamme_id', $ouvrage->gamme_id);
            })
            ->limit(6)
            ->get();

        return view('public.ouvrages.show', compact(
            'ouvrage',
            'composantsCount',
            'similaires'
        ));
    }

    /**
     * Affiche la composition détaillée d'un ouvrage
     */
    // public function composition(Ouvrage $ouvrage)
    // {
    //     $composition = $ouvrage->composants()
    //         ->with(['finitions', 'typeComposant'])
    //         ->orderBy('pivot_ordre')
    //         ->get();

    //     return view('public.ouvrages.composition', compact('ouvrage', 'composition'));
    // }

    /**
 * Affiche la composition détaillée d'un ouvrage
 */
    public function composition(Ouvrage $ouvrage)
    {
        $composition = $ouvrage->composants()
            ->with([
                'typeComposant',
                'finitions' => function ($query) {
                    $query->where('est_par_defaut', true);
                },
                'caracteristiques' => function ($query) {
                    $query->orderBy('ordre_affichage');
                },
            ])
            ->orderBy('pivot_ordre')
            ->get();
    
        // Calcul du poids total estimé
        $poidsTotal = 0;
        foreach ($composition as $composant) {
            if ($composant->poids_lineaire_kg_m && $composant->pivot->longueur_coupe_mm) {
                $poidsTotal += ($composant->poids_lineaire_kg_m * $composant->pivot->longueur_coupe_mm / 1000) * $composant->pivot->quantite;
            } elseif ($composant->poids_lineaire_kg_m) {
                $poidsTotal += ($composant->poids_lineaire_kg_m * ($composant->longueur_barre_mm ?? 6000) / 1000) * $composant->pivot->quantite;
            }
        }
    
        return view('public.ouvrages.composition', compact('ouvrage', 'composition', 'poidsTotal'));
    }
    /**
     * Version imprimable de la fiche ouvrage
     */
    public function print(Ouvrage $ouvrage)
    {
        $ouvrage->load([
            'gamme',
            'categorie',
            'composants' => function ($query) {
                $query->orderBy('pivot_ordre');
            },
            'caracteristiques' => function ($query) {
                $query->orderBy('ordre_affichage');
            }
        ]);

        return view('public.ouvrages.print', compact('ouvrage'));
    }

    /**
     * Téléchargement de la fiche technique en PDF
     * (Nécessite DomPDF ou autre package)
     */
    public function downloadPdf(Ouvrage $ouvrage)
    {
        // Note : nécessite l'installation de barryvdh/laravel-dompdf
        // $pdf = \PDF::loadView('public.ouvrages.pdf', compact('ouvrage'));
        // return $pdf->download("ouvrage-{$ouvrage->slug}.pdf");

        // Alternative : redirection vers une version imprimable
        return redirect()->route('ouvrages.print', $ouvrage->slug);
    }

    /**
     * Export CSV des ouvrages (avec filtres)
     */
    public function export(Request $request)
    {
        $query = Ouvrage::with(['gamme', 'categorie'])->actif();

        // Appliquer les mêmes filtres que l'index
        if ($request->has('categorie')) {
            $categorie = Categorie::where('slug', $request->categorie)->first();
            if ($categorie) {
                $query->where('categorie_id', $categorie->id);
            }
        }

        if ($request->has('gamme')) {
            $gamme = Gamme::where('slug', $request->gamme)->first();
            if ($gamme) {
                $query->where('gamme_id', $gamme->id);
            }
        }

        $ouvrages = $query->get();

        $filename = 'ouvrages_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($ouvrages) {
            $handle = fopen('php://output', 'w');

            // BOM pour Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes
            fputcsv($handle, [
                'Référence',
                'Nom',
                'Slug',
                'Gamme',
                'Catégorie',
                'Description courte',
                'Largeur min (mm)',
                'Largeur max (mm)',
                'Hauteur min (mm)',
                'Hauteur max (mm)',
                'Performance thermique',
                'Performance acoustique',
                'Actif',
                'Date de création',
            ]);

            // Lignes
            foreach ($ouvrages as $ouvrage) {
                fputcsv($handle, [
                    $ouvrage->reference,
                    $ouvrage->nom,
                    $ouvrage->slug,
                    $ouvrage->gamme?->nom ?? '',
                    $ouvrage->categorie?->nom ?? '',
                    $ouvrage->description_courte ?? '',
                    $ouvrage->largeur_min_mm ?? '',
                    $ouvrage->largeur_max_mm ?? '',
                    $ouvrage->hauteur_min_mm ?? '',
                    $ouvrage->hauteur_max_mm ?? '',
                    $ouvrage->performance_thermique ?? '',
                    $ouvrage->performance_acoustique ?? '',
                    $ouvrage->est_actif ? 'Oui' : 'Non',
                    $ouvrage->created_at?->format('d/m/Y H:i') ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Recherche autocomplete (pour la barre de recherche)
     */
    public function autocomplete(Request $request)
    {
        $search = $request->get('q');

        if (empty($search)) {
            return response()->json([]);
        }

        $ouvrages = Ouvrage::actif()
            ->where('nom', 'LIKE', "%{$search}%")
            ->orWhere('reference', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'nom', 'reference', 'slug']);

        return response()->json($ouvrages);
    }
}