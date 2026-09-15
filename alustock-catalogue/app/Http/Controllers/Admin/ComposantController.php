<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreComposantRequest;
use App\Http\Requests\Admin\UpdateComposantRequest;
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
        $validated['est_disponible'] = $request->has('est_disponible');

        $composant = Composant::create($validated);

        // Traiter les médias
        if ($request->hasFile('medias_fichiers')) {
            $this->handleMedias($composant, $request);
        }

        return redirect()
            ->route('admin.composants.edit', $composant)
            ->with('success', 'Composant créé avec succès.');
    }

    protected function handleMedias(Composant $composant, Request $request): void
    {
        $files = $request->file('medias_fichiers', []);
        $typeMedia = $request->input('medias_type_media', 'schema');

        if (empty($files)) return;

        $count = 0;

        foreach ($files as $file) {
            $filename = \Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/composants', $filename, 'public');

            $media = \App\Models\Media::create([
                'chemin_fichier' => $path,
                'titre' => $file->getClientOriginalName(),
                'type_media' => $typeMedia,
                'taille_octets' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'est_principal' => $count === 0,
            ]);

            $composant->medias()->attach($media->id, [
                'ordre' => $count + 1,
            ]);

            $count++;
        }
    }

    public function edit(Composant $composant)
    {
        $typesComposant = TypeComposant::orderBy('nom')->get();
        $gammes = Gamme::orderBy('ordre_affichage')->get();

        return view('admin.composants.edit', compact('composant', 'typesComposant', 'gammes'));
    }

    public function update(UpdateComposantRequest $request, Composant $composant)
    {
        $validated = $request->validated();
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