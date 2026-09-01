<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GammeRequest;
use App\Models\Gamme;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GammeController extends Controller
{
    public function index(Request $request)
    {
        $query = Gamme::query();

        if ($request->filled('search')) {
            $query->where('nom', 'LIKE', '%' . $request->search . '%');
        }

        $gammes = $query->orderBy('ordre')->paginate(10);
        return view('admin.gammes.index', compact('gammes'));
    }

    public function create()
    {
        return view('admin.gammes.create');
    }

    public function store(GammeRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nom']);

        Gamme::create($data);

        return redirect()->route('admin.gammes.index')
            ->with('success', 'Gamme créée avec succès.');
    }

    public function edit(Gamme $gamme)
    {
        return view('admin.gammes.edit', compact('gamme'));
    }

    public function update(GammeRequest $request, Gamme $gamme)
    {
        $data = $request->validated();

        if ($request->filled('nom') && $gamme->nom != $data['nom']) {
            $data['slug'] = Str::slug($data['nom']);
        }

        $gamme->update($data);

        return redirect()->route('admin.gammes.index')
            ->with('success', 'Gamme mise à jour avec succès.');
    }

    public function destroy(Gamme $gamme)
    {
        if ($gamme->ouvrages()->count() > 0) {
            return redirect()->route('admin.gammes.index')
                ->with('error', 'Cette gamme ne peut pas être supprimée car elle contient des ouvrages.');
        }

        $gamme->delete();

        return redirect()->route('admin.gammes.index')
            ->with('success', 'Gamme supprimée avec succès.');
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $position => $id) {
            Gamme::where('id', $id)->update(['ordre' => $position]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleStatus(Gamme $gamme)
    {
        $gamme->update(['is_active' => !$gamme->is_active]);
        return response()->json(['success' => true, 'status' => $gamme->is_active]);
    }
}