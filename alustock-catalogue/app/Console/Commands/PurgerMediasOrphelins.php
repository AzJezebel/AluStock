<?php

namespace App\Console\Commands;

use App\Support\MediaStorage;
use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurgerMediasOrphelins extends Command
{
    protected $signature = 'media:purger-orphelins
                            {--dry-run : Affiche ce qui serait supprimé, sans rien modifier}
                            {--force : Ne demande pas de confirmation}';

    protected $description = 'Supprime les liaisons, médias, caractéristiques et fichiers orphelins (entité supprimée).';

    /** Dossiers du disque "public" contenant des images gérées par l'application */
    private const DIRECTORIES = ['images/ouvrages', 'images/composants', 'images/categories'];

    public function handle(): int
    {
        // 1. Liaisons media_morph et caractéristiques dont l'entité n'existe plus
        $toutesLiaisons = DB::table('media_morph')->get();
        $estOrpheline = fn ($l) => !$this->entityExists($l->mediable_type, $l->mediable_id);
        $orphanLinks = $toutesLiaisons->filter($estOrpheline)->values();
        $orphanCharacteristics = DB::table('caracteristiques')->get()
            ->filter(fn ($c) => !$this->entityExists($c->caracterisable_type, $c->caracterisable_id))->values();

        // 2. Médias qui n'ont plus aucune liaison valide
        $idsAvecLiaisonValide = $toutesLiaisons->reject($estOrpheline)->pluck('media_id')->unique();
        $orphanMedias = Media::withTrashed()->whereNotIn('id', $idsAvecLiaisonValide)->get();

        // 3. Fichiers du disque non référencés (hors fichiers des médias orphelins, supprimés avec eux)
        $files = $this->orphanFiles($orphanMedias);

        $this->table(['Élément', 'Nombre'], [
            ['Liaisons media_morph orphelines', $orphanLinks->count()],
            ['Caractéristiques orphelines', $orphanCharacteristics->count()],
            ['Médias sans entité', $orphanMedias->count()],
            ['Autres fichiers non référencés (disque)', count($files)],
        ]);

        foreach (array_slice($files, 0, 15) as $f) {
            $this->line('  fichier : ' . $f);
        }
        if (count($files) > 15) {
            $this->line('  … et ' . (count($files) - 15) . ' autre(s)');
        }

        if ($orphanLinks->isEmpty() && $orphanCharacteristics->isEmpty() && $orphanMedias->isEmpty() && !$files) {
            $this->info('Rien à purger.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry-run : aucune modification. Relance sans --dry-run pour purger.');
            return self::SUCCESS;
        }

        if (!$this->option('force') && !$this->confirm('Supprimer définitivement ces éléments ?')) {
            $this->line('Annulé.');
            return self::SUCCESS;
        }

        // Base : en une transaction. Fichiers : après.
        $paths = $files;
        DB::transaction(function () use ($orphanLinks, $orphanCharacteristics, $orphanMedias, &$paths) {
            foreach ($orphanLinks as $l) {
                DB::table('media_morph')->where([
                    'media_id' => $l->media_id,
                    'mediable_id' => $l->mediable_id,
                    'mediable_type' => $l->mediable_type,
                ])->delete();
            }

            if ($orphanCharacteristics->isNotEmpty()) {
                DB::table('caracteristiques')->whereIn('id', $orphanCharacteristics->pluck('id'))->delete();
            }

            foreach ($orphanMedias as $media) {
                $paths = array_merge($paths, array_filter([$media->chemin_fichier, $media->thumbnail]));
                $media->forceDelete();
            }
        });

        foreach (array_unique($paths) as $path) {
            MediaStorage::disk()->delete($path);
        }

        $this->info('Purge terminée.');
        return self::SUCCESS;
    }

    /** L'entité pointée par une relation polymorphe existe-t-elle encore ? */
    private function entityExists(string $type, int|string $id): bool
    {
        $class = Relation::getMorphedModel($type) ?? $type;

        if (!class_exists($class)) {
            return false;
        }

        return DB::table((new $class)->getTable())->where('id', $id)->exists();
    }

    /** Fichiers des dossiers d'images qu'aucun média conservé ni aucune catégorie ne référence. */
    private function orphanFiles($orphanMedias): array
    {
        $removed = $orphanMedias->flatMap(fn ($m) => [$m->chemin_fichier, $m->thumbnail])->filter();

        $references = Media::withTrashed()->get(['chemin_fichier', 'thumbnail'])
            ->flatMap(fn ($m) => [$m->chemin_fichier, $m->thumbnail])
            ->merge(DB::table('categories')->whereNotNull('image')->pluck('image'))
            ->filter()
            ->flip();

        $disk = MediaStorage::disk();
        $orphans = [];

        foreach (self::DIRECTORIES as $directory) {
            foreach ($disk->allFiles($directory) as $path) {
                if (!isset($references[$path])) {
                    $orphans[] = $path;
                }
            }
        }

        // Les fichiers référencés par un média orphelin sont supprimés avec lui, on ne les recompte pas
        return array_values(array_diff($orphans, $removed->all()));
    }
}
