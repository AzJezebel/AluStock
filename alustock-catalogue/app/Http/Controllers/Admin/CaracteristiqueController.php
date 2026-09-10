<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Caracteristique;
use Illuminate\Http\Request;

class CaracteristiqueController extends Controller
{
    /**
     * Ajouter une caractéristique à un ouvrage
     */
    public function store(Request $request, Ouvrage $ouvrage)
    {
        $validated = $request->validate([
            'cle' => 'required|string|max:100',
            'valeur' => 'required|string',
            'unite' => 'nullable|string|max:20',
            'ordre_affichage' => 'nullable|integer|min:0',
        ]);

        // Définir l'ordre par défaut
        if (empty($validated['ordre_affichage'])) {
            $maxOrdre = $ouvrage->caracteristiques()->max('ordre_affichage') ?? 0;
            $validated['ordre_affichage'] = $maxOrdre + 1;
        }

        $ouvrage->caracteristiques()->create($validated);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Caractéristique ajoutée.');
    }

    /**
     * Mettre à jour une caractéristique
     */
    public function update(Request $request, Ouvrage $ouvrage, Caracteristique $caracteristique)
    {
        // Vérifier que la caractéristique appartient bien à l'ouvrage
        if ($caracteristique->caracterisable_id !== $ouvrage->id 
            || $caracteristique->caracterisable_type !== Ouvrage::class) {
            abort(403);
        }

        $validated = $request->validate([
            'cle' => 'required|string|max:100',
            'valeur' => 'required|string',
            'unite' => 'nullable|string|max:20',
            'ordre_affichage' => 'nullable|integer|min:0',
        ]);

        $caracteristique->update($validated);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Caractéristique mise à jour.');
    }

    /**
     * Supprimer une caractéristique
     */
    public function destroy(Ouvrage $ouvrage, Caracteristique $caracteristique)
    {
        if ($caracteristique->caracterisable_id !== $ouvrage->id 
            || $caracteristique->caracterisable_type !== Ouvrage::class) {
            abort(403);
        }

        $caracteristique->delete();

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Caractéristique supprimée.');
    }

    /**
     * Réordonner les caractéristiques
     */
    public function reorder(Request $request, Ouvrage $ouvrage)
    {
        $request->validate([
            'ordre' => 'required|array',
            'ordre.*' => 'integer|exists:caracteristiques,id',
        ]);

        foreach ($request->ordre as $position => $caracId) {
            Caracteristique::where('id', $caracId)
                ->where('caracterisable_id', $ouvrage->id)
                ->where('caracterisable_type', Ouvrage::class)
                ->update(['ordre_affichage' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}