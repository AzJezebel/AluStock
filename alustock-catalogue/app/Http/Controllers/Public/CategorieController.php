<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Liste toutes les catégories avec statistiques
     */
    public function index()
    {
        $categories = Categorie::withCount('ouvrages')
                               ->orderBy('nom')
                               ->get();

        $totalCategories = Categorie::count();
        $totalOuvrages = Categorie::withCount('ouvrages')->get()->sum('ouvrages_count');

        return view('public.categories.index', compact('categories', 'totalCategories', 'totalOuvrages'));
    }

    /**
     * Affiche le détail d'une catégorie avec ses ouvrages
     */
    public function show(Categorie $categorie)
    {
        $ouvrages = $categorie->ouvrages()
                              ->with(['gamme', 'categorie'])
                              ->actif()
                              ->paginate(12);

        // Comptage des composants associés à la catégorie
        $composantsCount = $categorie->ouvrages()
                                     ->withCount('composants')
                                     ->get()
                                     ->sum('composants_count');

        return view('public.categories.show', compact('categorie', 'ouvrages', 'composantsCount'));
    }

    /**
     * Liste tous les ouvrages d'une catégorie spécifique
     */
    public function ouvrages(Categorie $categorie)
    {
        $ouvrages = $categorie->ouvrages()
                              ->with(['gamme', 'categorie'])
                              ->actif()
                              ->paginate(20);

        return view('public.categories.ouvrages', compact('categorie', 'ouvrages'));
    }

    /**
     * Liste tous les composants d'une catégorie spécifique
     * (via les ouvrages de la catégorie)
     */
    public function composants(Categorie $categorie)
    {
        // Récupère les composants uniques des ouvrages de cette catégorie
        $composants = \App\Models\Composant::whereHas('ouvrages', function ($query) use ($categorie) {
            $query->where('categorie_id', $categorie->id)
                  ->where('est_actif', true);
        })
        ->with(['typeComposant', 'finitions'])
        ->disponible()
        ->paginate(20);

        return view('public.categories.composants', compact('categorie', 'composants'));
    }

    /**
     * Export CSV des catégories
     */
    public function export()
    {
        $categories = Categorie::withCount('ouvrages')->get();

        $filename = 'categories_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($categories) {
            $handle = fopen('php://output', 'w');

            // BOM pour Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // En-têtes
            fputcsv($handle, [
                'ID',
                'Nom',
                'Slug',
                'Description',
                'Icône',
                'Nombre d\'ouvrages',
                'Date de création',
            ]);

            // Lignes
            foreach ($categories as $categorie) {
                fputcsv($handle, [
                    $categorie->id,
                    $categorie->nom,
                    $categorie->slug,
                    $categorie->description,
                    $categorie->icone,
                    $categorie->ouvrages_count,
                    $categorie->created_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}