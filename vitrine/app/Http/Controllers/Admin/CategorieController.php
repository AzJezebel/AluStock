<?php
// app/Http/Controllers/Admin/CategorieController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategorieRequest;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $categories = Categorie::query()
            ->when($request->filled('search'), fn ($q) => $q->where('nom', 'LIKE', '%' . $request->search . '%'))
            ->ordered()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategorieRequest $request)
    {
        Categorie::create($this->payload($request));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Categorie $categorie)
    {
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(CategorieRequest $request, Categorie $categorie)
    {
        $categorie->update($this->payload($request));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Categorie $categorie)
    {
        if ($categorie->ouvrages()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cette catégorie ne peut pas être supprimée car elle contient des ouvrages.');
        }

        $categorie->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }

    public function toggleStatus(Categorie $categorie)
    {
        $categorie->update(['is_active' => ! $categorie->is_active]);

        return response()->json(['success' => true, 'status' => $categorie->is_active]);
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->input('order') as $position => $id) {
            Categorie::where('id', $id)->update(['ordre' => $position]);
        }

        return response()->json(['success' => true]);
    }

    /** Données validées + case à cocher + ordre par défaut (le slug est géré par le modèle). */
    private function payload(CategorieRequest $request): array
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['ordre']     = $data['ordre'] ?? 0;

        return $data;
    }
}