<?php

namespace App\Http\Controllers\Admin;

use App\Support\MediaStorage;
use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Ouvrage;
use App\Models\Composant;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Controllers\Admin\Concerns\GereUploadsMedias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    use GereUploadsMedias;

    /**
     * Upload pour un ouvrage
     */
    public function storeForOuvrage(StoreMediaRequest $request, Ouvrage $ouvrage)
    {
        $count = $this->transactionAvecFichiers(fn () => $this->enregistrerMedias(
            $ouvrage,
            $request->file('fichiers', []),
            'images/ouvrages',
            $request->input('type_media', 'schema')
        ));

        return back()->with('success', $count . ' image(s) ajoutée(s).');
    }

    /**
     * Upload pour un composant
     */
    public function storeForComposant(StoreMediaRequest $request, Composant $composant)
    {
        $count = $this->transactionAvecFichiers(fn () => $this->enregistrerMedias(
            $composant,
            $request->file('fichiers', []),
            'images/composants',
            $request->input('type_media', 'schema')
        ));

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
        $chemin = $media->chemin_fichier;

        // Base d'abord, en une transaction : si ça échoue, le fichier est encore là.
        DB::transaction(function () use ($media) {
            $media->ouvrages()->detach();
            $media->composants()->detach();
            $media->categories()->detach();
            $media->forceDelete();
        });

        // Fichier ensuite : un échec ici laisse au pire un fichier orphelin, jamais une image cassée.
        if ($chemin && MediaStorage::disk()->exists($chemin)) {
            MediaStorage::disk()->delete($chemin);
        }

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