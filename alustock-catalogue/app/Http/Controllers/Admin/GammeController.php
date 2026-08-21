<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gamme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GammeController extends Controller
{
    public function index()
    {
        $gammes = Gamme::orderBy('ordre_affichage')->paginate(20);
        return view('admin.gammes.index', compact('gammes'));
    }

    public function create()
    {
        return view('admin.gammes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:gammes',
            'description' => 'nullable|string',
            'image_cover' => 'nullable|string|max:255',
            'ordre_affichage' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($request->nom);

        Gamme::create($validated);

        return redirect()->route('admin.gammes.index')
            ->with('success', 'Gamme créée avec succès.');
    }

    public function edit(Gamme $gamme)
    {
        return view('admin.gammes.edit', compact('gamme'));
    }

    public function update(Request $request, Gamme $gamme)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:gammes,nom,' . $gamme->id,
            'description' => 'nullable|string',
            'image_cover' => 'nullable|string|max:255',
            'ordre_affichage' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($request->nom);

        $gamme->update($validated);

        return redirect()->route('admin.gammes.index')
            ->with('success', 'Gamme mise à jour avec succès.');
    }

    public function destroy(Gamme $gamme)
    {
        $gamme->delete();
        return redirect()->route('admin.gammes.index')
            ->with('success', 'Gamme supprimée avec succès.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->orders as $order) {
            Gamme::where('id', $order['id'])
                ->update(['ordre_affichage' => $order['position']]);
        }
        return response()->json(['success' => true]);
    }
}