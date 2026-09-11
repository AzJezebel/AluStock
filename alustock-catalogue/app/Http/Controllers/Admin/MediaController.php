<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Ouvrage;
use App\Models\Composant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Upload pour un ouvrage
     */
    public function storeForOuvrage(Request $request, Ouvrage $ouvrage)
    {
        $request->validate([
            'fichiers' => 'required|array',
            'fichiers.*' => 'file|mimes:png,jpg,jpeg|max:5120',
            'type_media' => 'nullable|in:schema,photo,rendu_3d',
        ]);

        $files = $request->file('fichiers', []);
        $typeMedia = $request->input('type_media', 'schema');

        $maxOrdre = $ouvrage->medias()->max('media_morph.ordre') ?? 0;
        $count = 0;

        foreach ($files as $file) {
            // 1. Upload du fichier
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/ouvrages', $filename, 'public');

            // 2. Créer le média
            $media = Media::create([
                'chemin_fichier' => $path,
                'titre' => $file->getClientOriginalName(),
                'type_media' => $typeMedia,
                'taille_octets' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'est_principal' => $ouvrage->medias()->count() === 0 && $count === 0,
            ]);

            // 3. Attacher à l'ouvrage
            $ouvrage->medias()->attach($media->id, [
                'ordre' => $maxOrdre + $count + 1,
            ]);

            $count++;
        }

        return back()->with('success', $count . ' image(s) ajoutée(s).');
    }

    /**
     * Upload pour un composant
     */
    public function storeForComposant(Request $request, Composant $composant)
    {
        $request->validate([
            'fichiers' => 'required|array',
            'fichiers.*' => 'file|mimes:png,jpg,jpeg|max:5120',
            'type_media' => 'nullable|in:schema,photo,rendu_3d',
        ]);

        $files = $request->file('fichiers', []);
        $typeMedia = $request->input('type_media', 'schema');

        $maxOrdre = $composant->medias()->max('media_morph.ordre') ?? 0;
        $count = 0;

        foreach ($files as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/composants', $filename, 'public');

            $media = Media::create([
                'chemin_fichier' => $path,
                'titre' => $file->getClientOriginalName(),
                'type_media' => $typeMedia,
                'taille_octets' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'est_principal' => $composant->medias()->count() === 0 && $count === 0,
            ]);

            $composant->medias()->attach($media->id, [
                'ordre' => $maxOrdre + $count + 1,
            ]);

            $count++;
        }

        return back()->with('success', $count . ' image(s) ajoutée(s).');
    }

    /**
     * Définir un média comme principal
     */
    public function setPrincipal(Request $request, Media $media)
    {
        $entityType = $request->input('entity_type');
        $entityId = $request->input('entity_id');

        // Retirer le flag de tous les médias de l'entité
        $entity = $entityType === 'ouvrage' 
            ? Ouvrage::findOrFail($entityId) 
            : Composant::findOrFail($entityId);

        foreach ($entity->medias as $m) {
            $m->update(['est_principal' => false]);
        }

        $media->update(['est_principal' => true]);

        return back()->with('success', 'Image principale définie.');
    }

    /**
     * Supprimer un média
     */
    public function destroy(Request $request, Media $media)
    {
        // Supprimer le fichier physique
        if ($media->chemin_fichier && Storage::disk('public')->exists($media->chemin_fichier)) {
            Storage::disk('public')->delete($media->chemin_fichier);
        }

        // Détacher de toutes les entités
        $media->ouvrages()->detach();
        $media->composants()->detach();
        $media->gammes()->detach();
        $media->categories()->detach();

        // Supprimer le média
        $media->forceDelete();

        return back()->with('success', 'Image supprimée.');
    }

    /**
     * Mettre à jour le titre
     */
    public function update(Request $request, Media $media)
    {
        $media->update([
            'titre' => $request->input('titre'),
        ]);

        return response()->json(['success' => true]);
    }
}