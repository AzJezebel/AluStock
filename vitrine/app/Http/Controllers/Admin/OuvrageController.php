<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OuvrageRequest;
use App\Models\Ouvrage;
use App\Models\Categorie;
use App\Models\Gamme;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OuvrageController extends Controller
{
    public function index(Request $request)
    {
        $query = Ouvrage::with(['categorie', 'gamme']);

        // Filtres
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('titre', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('reference', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->filled('gamme')) {
            $query->where('gamme_id', $request->gamme);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == 'active');
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured == 'true');
        }

        $ouvrages = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $categories = Categorie::orderBy('nom')->get();
        $gammes = Gamme::orderBy('nom')->get();

        return view('admin.ouvrages.index', compact('ouvrages', 'categories', 'gammes'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        $gammes = Gamme::orderBy('nom')->get();
        return view('admin.ouvrages.create', compact('categories', 'gammes'));
    }

    public function store(OuvrageRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['titre']);

        $ouvrage = Ouvrage::create($data);

        // Gestion des images
        if ($request->hasFile('main_image')) {
            $this->handleMainImage($request->file('main_image'), $ouvrage);
        }

        if ($request->hasFile('gallery_images')) {
            $this->handleGalleryImages($request->file('gallery_images'), $ouvrage);
        }

        return redirect()->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage créé avec succès.');
    }

    public function show(Ouvrage $ouvrage)
    {
        $ouvrage->load(['categorie', 'gamme', 'medias']);
        return view('admin.ouvrages.show', compact('ouvrage'));
    }

    public function edit(Ouvrage $ouvrage)
    {
        $categories = Categorie::orderBy('nom')->get();
        $gammes = Gamme::orderBy('nom')->get();
        $ouvrage->load('medias');
        return view('admin.ouvrages.edit', compact('ouvrage', 'categories', 'gammes'));
    }

    public function update(OuvrageRequest $request, Ouvrage $ouvrage)
    {
        $data = $request->validated();

        if ($request->filled('titre') && $ouvrage->titre != $data['titre']) {
            $data['slug'] = Str::slug($data['titre']);
        }

        $ouvrage->update($data);

        // Gestion des images
        if ($request->hasFile('main_image')) {
            $this->handleMainImage($request->file('main_image'), $ouvrage, true);
        }

        if ($request->hasFile('gallery_images')) {
            $this->handleGalleryImages($request->file('gallery_images'), $ouvrage);
        }

        return redirect()->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage mis à jour avec succès.');
    }

    public function destroy(Ouvrage $ouvrage)
    {
        // Supprimer les médias associés
        foreach ($ouvrage->medias as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        $ouvrage->delete();

        return redirect()->route('admin.ouvrages.index')
            ->with('success', 'Ouvrage supprimé avec succès.');
    }

    public function toggleStatus(Ouvrage $ouvrage)
    {
        $ouvrage->update(['is_active' => !$ouvrage->is_active]);
        return response()->json([
            'success' => true,
            'status' => $ouvrage->is_active
        ]);
    }

    public function toggleFeatured(Ouvrage $ouvrage)
    {
        $ouvrage->update(['is_featured' => !$ouvrage->is_featured]);
        return response()->json([
            'success' => true,
            'featured' => $ouvrage->is_featured
        ]);
    }

    public function deleteImage(Ouvrage $ouvrage, Media $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }

    public function reorderImages(Request $request, Ouvrage $ouvrage)
    {
        $order = $request->input('order', []);
        foreach ($order as $position => $id) {
            Media::where('id', $id)->update(['order' => $position]);
        }

        return response()->json(['success' => true]);
    }

    // Méthodes privées pour la gestion des images
    private function handleMainImage($file, Ouvrage $ouvrage, $replace = false)
    {
        if ($replace && $ouvrage->mainImage) {
            Storage::disk('public')->delete($ouvrage->mainImage->file_path);
            $ouvrage->mainImage->delete();
        }

        $path = $file->store('ouvrages/main/' . $ouvrage->id, 'public');
        
        Media::create([
            'model_type' => Ouvrage::class,
            'model_id' => $ouvrage->id,
            'collection_name' => 'main',
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'is_primary' => true,
            'order' => 0
        ]);
    }

    private function handleGalleryImages($files, Ouvrage $ouvrage)
    {
        $order = Media::where('model_type', Ouvrage::class)
            ->where('model_id', $ouvrage->id)
            ->where('collection_name', 'gallery')
            ->count();

        foreach ($files as $file) {
            $path = $file->store('ouvrages/gallery/' . $ouvrage->id, 'public');
            
            Media::create([
                'model_type' => Ouvrage::class,
                'model_id' => $ouvrage->id,
                'collection_name' => 'gallery',
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'is_primary' => false,
                'order' => $order++
            ]);
        }
    }
}