<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    /**
     * Liste des catégories
     */
    public function index(Request $request)
    {
        $query = Categorie::withCount('ouvrages');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'nom');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = ['nom', 'created_at', 'ouvrages_count'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('nom');
        }

        $categories = $query->paginate(20)->withQueryString();

        return view('admin.categories.index', compact('categories', 'sort', 'direction'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:categories,nom',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'supprimer_image' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->nom);

        unset($validated['supprimer_image']);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->storeAs('images/categories', Str::uuid() . '.' . $request->file('image')->extension(), 'public');
        }

        Categorie::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Categorie $categorie)
    {
        $categorie->loadCount('ouvrages');
        //$ouvragesCount = $categorie->ouvrages()->count();


        return view('admin.categories.edit', compact('categorie'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:categories,nom,' . $categorie->id,
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'supprimer_image' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        if ($request->nom !== $categorie->nom) {
            $validated['slug'] = Str::slug($request->nom);
        }

        unset($validated['supprimer_image']);
        if ($request->hasFile('image')) {
            $this->supprimerImage($categorie);
            $validated['image'] = $request->file('image')
                ->storeAs('images/categories', Str::uuid() . '.' . $request->file('image')->extension(), 'public');
        } elseif ($request->boolean('supprimer_image')) {
            $this->supprimerImage($categorie);
            $validated['image'] = null;
        } else {
            unset($validated['image']);
        }

        $categorie->update($validated);

        return redirect()
            ->route('admin.categories.edit', $categorie)
            ->with('success', 'Catégorie mise à jour.');
    }

    /**
     * Suppression
     */
    public function destroy(Categorie $categorie)
    {
        $this->supprimerImage($categorie);
        $categorie->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée.');
    }

    private function supprimerImage(Categorie $categorie): void
    {
        if ($categorie->image && Storage::disk('public')->exists($categorie->image)) {
            Storage::disk('public')->delete($categorie->image);
        }
    }
}
