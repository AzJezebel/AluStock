<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Composant;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    /**
     * Liste la composition d'un ouvrage
     */
    public function index(Ouvrage $ouvrage)
    {
        $composition = $ouvrage->composants()
            ->with(['typeComposant', 'finitions'])
            ->orderBy('pivot_ordre')
            ->get();

        return view('admin.ouvrages.composition', compact('ouvrage', 'composition'));
    }

    /**
     * Ajouter un composant à la composition
     */
    public function store(Request $request, Ouvrage $ouvrage)
    {
        $validated = $request->validate([
            'composant_id' => 'required|exists:composants,id',
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:20',
            'ordre' => 'nullable|integer|min:0',
            'commentaire' => 'nullable|string',
        ]);

        // Vérifier que le composant n'est pas déjà dans la composition
        if ($ouvrage->composants()->wherePivot('composant_id', $validated['composant_id'])->exists()) {
            return back()->with('error', 'Ce composant est déjà dans la composition.');
        }

        // Définir l'ordre par défaut
        if (empty($validated['ordre'])) {
            $maxOrdre = $ouvrage->composants()->max('composition_ouvrage.ordre') ?? 0;
            $validated['ordre'] = $maxOrdre + 1;
        }

        $ouvrage->composants()->attach($validated['composant_id'], [
            'quantite' => $validated['quantite'],
            'unite' => $validated['unite'],
            'ordre' => $validated['ordre'],
            'commentaire' => $validated['commentaire'],
        ]);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Composant ajouté à la composition.');
    }

    public function storeNew(Request $request, Ouvrage $ouvrage)
    {
        $validated = $request->validate([
            // Identification
            'reference' => 'required|string|max:50|unique:composants,reference',
            'designation' => 'required|string|max:200',
            'type_composant_id' => 'nullable|exists:types_composant,id',
            'matiere' => 'nullable|string|max:100',
    
            // Caractéristiques techniques (profilés)
            'longueur_barre_mm' => 'nullable|integer|min:0',
            'section_largeur_mm' => 'nullable|numeric|min:0',
            'section_hauteur_mm' => 'nullable|numeric|min:0',
            'epaisseur_paroi_mm' => 'nullable|numeric|min:0',
            'poids_lineaire_kg_m' => 'nullable|numeric|min:0',
            'poids_lineaire_lbs_ft' => 'nullable|numeric|min:0',
            'moment_inertie_cm4' => 'nullable|numeric|min:0',
            'perimetre_mm' => 'nullable|numeric|min:0',
    
            // Composition
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:20',
        ]);
    
        // Créer le composant
        $composant = Composant::create([
            'reference' => $validated['reference'],
            'designation' => $validated['designation'],
            'type_composant_id' => $validated['type_composant_id'] ?? null,
            'matiere' => $validated['matiere'] ?? null,
            'longueur_barre_mm' => $validated['longueur_barre_mm'] ?? null,
            'section_largeur_mm' => $validated['section_largeur_mm'] ?? null,
            'section_hauteur_mm' => $validated['section_hauteur_mm'] ?? null,
            'epaisseur_paroi_mm' => $validated['epaisseur_paroi_mm'] ?? null,
            'poids_lineaire_kg_m' => $validated['poids_lineaire_kg_m'] ?? null,
            'poids_lineaire_lbs_ft' => $validated['poids_lineaire_lbs_ft'] ?? null,
            'moment_inertie_cm4' => $validated['moment_inertie_cm4'] ?? null,
            'perimetre_mm' => $validated['perimetre_mm'] ?? null,
            'est_disponible' => true,
        ]);
    
        // Ajouter à la composition
        $maxOrdre = $ouvrage->composants()->max('composition_ouvrage.ordre') ?? 0;
        
        $ouvrage->composants()->attach($composant->id, [
            'quantite' => $validated['quantite'],
            'unite' => $validated['unite'],
            'ordre' => $maxOrdre + 1,
        ]);
    
        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Composant créé et ajouté à la composition.');
    }

    /**
     * Mettre à jour une ligne de composition
     */
    public function update(Request $request, Ouvrage $ouvrage, Composant $composant)
    {
        $validated = $request->validate([
            'quantite' => 'required|numeric|min:0.01',
            'unite' => 'required|string|max:20',
            'ordre' => 'nullable|integer|min:0',
            'commentaire' => 'nullable|string',
        ]);

        $ouvrage->composants()->updateExistingPivot($composant->id, [
            'quantite' => $validated['quantite'],
            'unite' => $validated['unite'],
            'ordre' => $validated['ordre'] ?? 0,
            'commentaire' => $validated['commentaire'],
        ]);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Composition mise à jour.');
    }

    /**
     * Retirer un composant de la composition
     */
    public function destroy(Ouvrage $ouvrage, Composant $composant)
    {
        $ouvrage->composants()->detach($composant->id);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Composant retiré de la composition.');
    }

    /**
     * Réordonner la composition
     */
    public function reorder(Request $request, Ouvrage $ouvrage)
    {
        $request->validate([
            'ordre' => 'required|array',
            'ordre.*' => 'integer|exists:composants,id',
        ]);

        foreach ($request->ordre as $position => $composantId) {
            $ouvrage->composants()->updateExistingPivot($composantId, [
                'ordre' => $position + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }
}