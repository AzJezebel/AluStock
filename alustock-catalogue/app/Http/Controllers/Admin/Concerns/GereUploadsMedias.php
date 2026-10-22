<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Upload d'images + écritures en base dans une transaction.
 *
 * Une transaction annule la base mais pas le disque : on garde la liste des fichiers
 * écrits pendant l'opération et on les supprime si elle échoue.
 */
trait GereUploadsMedias
{
    /** @var string[] Chemins (disque "public") écrits pendant l'opération en cours */
    private array $fichiersEcrits = [];

    /**
     * Exécute $operation dans une transaction. Si elle échoue : rollback de la base,
     * suppression des fichiers déjà stockés, puis l'exception est relancée.
     */
    protected function transactionAvecFichiers(callable $operation): mixed
    {
        $this->fichiersEcrits = [];

        try {
            return DB::transaction($operation);
        } catch (Throwable $e) {
            foreach ($this->fichiersEcrits as $chemin) {
                Storage::disk('public')->delete($chemin);
            }
            $this->fichiersEcrits = [];

            throw $e;
        }
    }

    /**
     * Stocke les fichiers, crée les Media et les attache à l'entité.
     * À appeler depuis transactionAvecFichiers().
     *
     * @return int nombre d'images ajoutées
     */
    protected function enregistrerMedias(Model $entite, array $fichiers, string $dossier, string $typeMedia = 'schema'): int
    {
        if (empty($fichiers)) {
            return 0;
        }

        $dejaDesMedias = $entite->medias()->exists();
        $maxOrdre = $entite->medias()->max('media_morph.ordre') ?? 0;
        $count = 0;

        foreach ($fichiers as $fichier) {
            $nom = Str::uuid() . '.' . $fichier->getClientOriginalExtension();
            $chemin = $fichier->storeAs($dossier, $nom, 'public');
            $this->fichiersEcrits[] = $chemin;

            $media = Media::create([
                'chemin_fichier' => $chemin,
                'titre' => $fichier->getClientOriginalName(),
                'type_media' => $typeMedia,
                'taille_octets' => $fichier->getSize(),
                'mime_type' => $fichier->getMimeType(),
                'est_principal' => !$dejaDesMedias && $count === 0,
            ]);

            $entite->medias()->attach($media->id, ['ordre' => $maxOrdre + $count + 1]);
            $count++;
        }

        return $count;
    }

    /**
     * Supprime une entité (ouvrage, composant…) avec ses médias et caractéristiques.
     * Base d'abord, en une transaction ; fichiers ensuite (un échec disque laisse au pire un
     * fichier orphelin). Un média encore utilisé par une autre entité est conservé.
     */
    protected function supprimerAvecMedias(Model $entite): void
    {
        $chemins = [];

        DB::transaction(function () use ($entite, &$chemins) {
            $medias = $entite->medias()->get();
            $entite->medias()->detach();

            foreach ($medias as $media) {
                $encoreUtilise = $media->ouvrages()->exists()
                    || $media->composants()->exists()
                    || $media->categories()->exists();

                if ($encoreUtilise) continue;

                $chemins = array_merge($chemins, array_filter([$media->chemin_fichier, $media->thumbnail]));
                $media->forceDelete();
            }

            if (method_exists($entite, 'caracteristiques')) {
                $entite->caracteristiques()->delete();
            }

            // Pas de clé étrangère sur documentable_id : on détache à la main.
            if (method_exists($entite, 'documents')) {
                $entite->documents()->detach();
            }

            $entite->delete();
        });

        foreach ($chemins as $chemin) {
            Storage::disk('public')->delete($chemin);
        }
    }
}
