<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreComposantRequest;
use App\Models\Composant;
use App\Models\Gamme;
use App\Models\TypeComposant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ComposantController extends Controller
{
    public function index(Request $request)
    {
        $query = Composant::with(['typeComposant', 'gamme']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('designation', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type_composant_id', $request->type);
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['reference', 'designation', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $composants = $query->paginate(20)->withQueryString();
        $typesComposant = TypeComposant::orderBy('nom')->get();

        return view('admin.composants.index', compact('composants', 'typesComposant', 'sort', 'direction'));
    }

    public function create()
    {
        $typesComposant = TypeComposant::orderBy('nom')->get();
        $gammes = Gamme::orderBy('ordre_affichage')->get();

        return view('admin.composants.create', compact('typesComposant', 'gammes'));
    }

    public function store(StoreComposantRequest $request)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->designation);
        $validated['est_disponible'] = $request->has('est_disponible');

        Composant::create($validated);

        return redirect()
            ->route('admin.composants.index')
            ->with('success', 'Composant créé avec succès.');
    }

    public function edit(Composant $composant)
    {
        $typesComposant = TypeComposant::orderBy('nom')->get();
        $gammes = Gamme::orderBy('ordre_affichage')->get();

        return view('admin.composants.edit', compact('composant', 'typesComposant', 'gammes'));
    }

    public function update(StoreComposantRequest $request, Composant $composant)
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($request->designation);
        $validated['est_disponible'] = $request->has('est_disponible');

        $composant->update($validated);

        return redirect()
            ->route('admin.composants.index')
            ->with('success', 'Composant mis à jour.');
    }

    public function destroy(Composant $composant)
    {
        $composant->delete();

        return redirect()
            ->route('admin.composants.index')
            ->with('success', 'Composant supprimé.');
    }
}