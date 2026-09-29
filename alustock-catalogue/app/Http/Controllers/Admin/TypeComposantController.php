<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TypeComposant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TypeComposantController extends Controller
{
    /**
     * Liste des types de composant
     */
    public function index(Request $request)
    {
        $query = TypeComposant::withCount('composants');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'nom');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = ['nom', 'created_at', 'composants_count'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('nom');
        }

        $types = $query->paginate(20)->withQueryString();

        return view('admin.types-composant.index', compact('types', 'sort', 'direction'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.types-composant.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:types_composant,nom',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->nom);

        TypeComposant::create($validated);

        return redirect()
            ->route('admin.types-composant.index')
            ->with('success', 'Type de composant créé avec succès.');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(TypeComposant $typeComposant)
    {
        $typeComposant->loadCount('composants');

        return view('admin.types-composant.edit', compact('typeComposant'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, TypeComposant $typeComposant)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:types_composant,nom,' . $typeComposant->id,
            'description' => 'nullable|string',
        ]);

        if ($request->nom !== $typeComposant->nom) {
            $validated['slug'] = Str::slug($request->nom);
        }

        $typeComposant->update($validated);

        return redirect()
            ->route('admin.types-composant.edit', $typeComposant)
            ->with('success', 'Type de composant mis à jour.');
    }

    /**
     * Suppression
     */
    public function destroy(TypeComposant $typeComposant)
    {
        $typeComposant->delete();

        return redirect()
            ->route('admin.types-composant.index')
            ->with('success', 'Type de composant supprimé.');
    }
}