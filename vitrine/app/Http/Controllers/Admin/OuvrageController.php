<?php
// app/Http/Controllers/Admin/OuvrageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OuvrageRequest;
use App\Models\Categorie;
use App\Models\Media;
use App\Models\Ouvrage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class OuvrageController extends Controller
{
    public function index(Request $request)
    {
        $ouvrages = Ouvrage::with(['categorie', 'mainImage'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('titre', 'LIKE', '%' . $request->search . '%')
                      ->orWhere('reference', 'LIKE', '%' . $request->search . '%');
                });
            })
            ->when($request->filled('categorie'), fn ($q) => $q->where('categorie_id', $request->categorie))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'))
            ->when($request->filled('featured'), fn ($q) => $q->where('is_featured', $request->featured === 'true'))
            ->latest()
            ->paginate(10);

        $categories = Categorie::ordered()->get();

        return view('admin.ouvrages.index', compact('ouvrages', 'categories'));
    }

    public function create()
    {
        $categories = Categorie::ordered()->get();

        return view('admin.ouvrages.create', compact('categories'));
    }

    public function store(OuvrageRequest $request)
    {
        $ouvrage = Ouvrage::create($this->payload($request)); // slug : géré par le modèle

        $this->saveImages($request, $ouvrage);

        return redirect()->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage créé avec succès.');
    }

    public function show(Ouvrage $ouvrage)
    {
        $ouvrage->load(['categorie', 'mainImage', 'gallery']);

        return view('admin.ouvrages.show', compact('ouvrage'));
    }

    public function edit(Ouvrage $ouvrage)
    {
        $categories = Categorie::ordered()->get();
        $ouvrage->load(['mainImage', 'gallery']);

        return view('admin.ouvrages.edit', compact('ouvrage', 'categories'));
    }

    public function update(OuvrageRequest $request, Ouvrage $ouvrage)
    {
        $ouvrage->update($this->payload($request));

        $this->saveImages($request, $ouvrage, replaceMain: true);

        return redirect()->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage mis à jour avec succès.');
    }

    public function destroy(Ouvrage $ouvrage)
    {
        $ouvrage->delete(); // les médias (lignes + fichiers) partent avec, voir Ouvrage::booted()

        return redirect()->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage supprimé avec succès.');
    }

    public function toggleStatus(Ouvrage $ouvrage)
    {
        $ouvrage->update(['is_active' => ! $ouvrage->is_active]);

        return response()->json(['success' => true, 'status' => $ouvrage->is_active]);
    }

    public function toggleFeatured(Ouvrage $ouvrage)
    {
        $ouvrage->update(['is_featured' => ! $ouvrage->is_featured]);

        return response()->json(['success' => true, 'featured' => $ouvrage->is_featured]);
    }

    public function deleteImage(Ouvrage $ouvrage, Media $media)
    {
        $this->assertOwnedBy($ouvrage, $media);

        $media->delete(); // le fichier est supprimé par Media::booted()

        return back()->with('success', 'Image supprimée avec succès.');
    }

    public function reorderImages(Request $request, Ouvrage $ouvrage)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer']);

        foreach ($request->input('order') as $position => $id) {
            // limité aux médias de CET ouvrage
            $ouvrage->medias()->where('id', $id)->update(['order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------ privé

    /** Données validées + cases à cocher (une case décochée n'envoie rien). */
    private function payload(OuvrageRequest $request): array
    {
        $data = collect($request->validated())->except(['main_image', 'gallery_images'])->all();
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    private function saveImages(OuvrageRequest $request, Ouvrage $ouvrage, bool $replaceMain = false): void
    {
        if ($request->hasFile('main_image')) {
            if ($replaceMain) {
                $ouvrage->mainImage?->delete();
            }
            $this->storeMedia($request->file('main_image'), $ouvrage, primary: true, order: 0);
        }

        if ($request->hasFile('gallery_images')) {
            $next = $ouvrage->gallery()->count();
            foreach ($request->file('gallery_images') as $file) {
                $this->storeMedia($file, $ouvrage, primary: false, order: $next++);
            }
        }
    }

    private function storeMedia(UploadedFile $file, Ouvrage $ouvrage, bool $primary, int $order): Media
    {
        $folder = $primary ? 'main' : 'gallery'; // simple rangement disque, sans rôle en base

        return $ouvrage->medias()->create([
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $file->store("ouvrages/{$folder}/{$ouvrage->id}", Media::disk()),
            'file_size'  => $file->getSize(),
            'mime_type'  => $file->getMimeType(),
            'is_primary' => $primary,
            'order'      => $order,
        ]);
    }

    private function assertOwnedBy(Ouvrage $ouvrage, Media $media): void
    {
        abort_unless(
            $media->model_type === Ouvrage::class && (int) $media->model_id === $ouvrage->id,
            404
        );
    }
}