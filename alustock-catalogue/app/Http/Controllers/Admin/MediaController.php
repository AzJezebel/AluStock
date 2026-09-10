<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ouvrage;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Upload d'un média pour un ouvrage
     */
    public function store(Request $request, Ouvrage $ouvrage)
    {
        $validated = $request->validate([
            'fichier' => 'required|file|mimes:png,jpg,jpeg,webp,svg|max:5120', // 5 Mo max
            'titre' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'type_media' => 'required|in:image,rendu_3d,plan',
            'est_principal' => 'boolean',
        ]);

        // Upload du fichier
        $file = $request->file('fichier');
        $filename = Str::slug($ouvrage->slug) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('medias/ouvrages', $filename, 'public');

        // Créer le média
        $media = Media::create([
            'chemin_fichier' => $path,
            'titre' => $validated['titre'] ?? $file->getClientOriginalName(),
            'description' => $validated['description'] ?? null,
            'type_media' => $validated['type_media'],
            'est_principal' => $request->has('est_principal'),
        ]);

        // Attacher le média à l'ouvrage
        $maxOrdre = $ouvrage->medias()->max('media_morph.ordre') ?? 0;
        $ouvrage->medias()->attach($media->id, [
            'ordre' => $maxOrdre + 1,
        ]);

        // Si c'est le média principal, retirer le flag des autres
        if ($request->has('est_principal')) {
            $this->setPrincipal($ouvrage, $media);
        }

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Média ajouté avec succès.');
    }

    /**
     * Supprimer un média
     */
    public function destroy(Ouvrage $ouvrage, Media $media)
    {
        // Détacher de l'ouvrage
        $ouvrage->medias()->detach($media->id);

        // Supprimer le fichier
        if (Storage::disk('public')->exists($media->chemin_fichier)) {
            Storage::disk('public')->delete($media->chemin_fichier);
        }

        // Supprimer le média (s'il n'est plus utilisé)
        $media->delete();

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Média supprimé.');
    }

    /**
     * Définir un média comme principal
     */
    public function setPrincipal(Ouvrage $ouvrage, Media $media)
    {
        // Retirer le flag de tous les médias de l'ouvrage
        $ouvrage->medias()->update(['est_principal' => false]);

        // Mettre le flag sur le média choisi
        $media->update(['est_principal' => true]);

        return redirect()
            ->route('admin.ouvrages.edit', $ouvrage)
            ->with('success', 'Média principal défini.');
    }

    /**
     * Réordonner les médias
     */
    public function reorder(Request $request, Ouvrage $ouvrage)
    {
        $request->validate([
            'ordre' => 'required|array',
            'ordre.*' => 'integer|exists:medias,id',
        ]);

        foreach ($request->ordre as $position => $mediaId) {
            $ouvrage->medias()->updateExistingPivot($mediaId, [
                'ordre' => $position + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }
}