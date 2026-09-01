<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategorieRequest;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $query = Categorie::query();

        if ($request->filled('search')) {
            $query->where('nom', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('ordre')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategorieRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nom']);

        Categorie::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Categorie $categorie)
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(CategorieRequest $request, Categorie $categorie)
    {
        $data = $request->validated();

        if ($request->filled('nom') && $categorie->nom != $data['nom']) {
            $data['slug'] = Str::slug($data['nom']);
        }

        $categorie->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Categorie $categorie)
    {
        // Vérifier si des ouvrages sont associés
        if ($categorie->ouvrages()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cette catégorie ne peut pas être supprimée car elle contient des ouvrages.');
        }

        $categorie->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $position => $id) {
            Categorie::where('id', $id)->update(['ordre' => $position]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleStatus(Categorie $categorie)
    {
        $categorie->update(['is_active' => !$categorie->is_active]);
        return response()->json(['success' => true, 'status' => $categorie->is_active]);
    }
}