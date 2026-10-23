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
    private const DOSSIERS = ['images/ouvrages', 'images/composants', 'images/categories'];

    public function handle(): int
    {
        // 1. Liaisons media_morph et caractéristiques dont l'entité n'existe plus
        $toutesLiaisons = DB::table('media_morph')->get();
        $estOrpheline = fn ($l) => !$this->entiteExiste($l->mediable_type, $l->mediable_id);
        $liaisonsOrphelines = $toutesLiaisons->filter($estOrpheline)->values();
        $caracsOrphelines = DB::table('caracteristiques')->get()
            ->filter(fn ($c) => !$this->entiteExiste($c->caracterisable_type, $c->caracterisable_id))->values();

        // 2. Médias qui n'ont plus aucune liaison valide
        $idsAvecLiaisonValide = $toutesLiaisons->reject($estOrpheline)->pluck('media_id')->unique();
        $mediasOrphelins = Media::withTrashed()->whereNotIn('id', $idsAvecLiaisonValide)->get();

        // 3. Fichiers du disque non référencés (hors fichiers des médias orphelins, supprimés avec eux)
        $fichiers = $this->fichiersOrphelins($mediasOrphelins);

        $this->table(['Élément', 'Nombre'], [
            ['Liaisons media_morph orphelines', $liaisonsOrphelines->count()],
            ['Caractéristiques orphelines', $caracsOrphelines->count()],
            ['Médias sans entité', $mediasOrphelins->count()],
            ['Autres fichiers non référencés (disque)', count($fichiers)],
        ]);

        foreach (array_slice($fichiers, 0, 15) as $f) {
            $this->line('  fichier : ' . $f);
        }
        if (count($fichiers) > 15) {
            $this->line('  … et ' . (count($fichiers) - 15) . ' autre(s)');
        }

        if ($liaisonsOrphelines->isEmpty() && $caracsOrphelines->isEmpty() && $mediasOrphelins->isEmpty() && !$fichiers) {
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
        $chemins = $fichiers;
        DB::transaction(function () use ($liaisonsOrphelines, $caracsOrphelines, $mediasOrphelins, &$chemins) {
            foreach ($liaisonsOrphelines as $l) {
                DB::table('media_morph')->where([
                    'media_id' => $l->media_id,
                    'mediable_id' => $l->mediable_id,
                    'mediable_type' => $l->mediable_type,
                ])->delete();
            }

            if ($caracsOrphelines->isNotEmpty()) {
                DB::table('caracteristiques')->whereIn('id', $caracsOrphelines->pluck('id'))->delete();
            }

            foreach ($mediasOrphelins as $media) {
                $chemins = array_merge($chemins, array_filter([$media->chemin_fichier, $media->thumbnail]));
                $media->forceDelete();
            }
        });

        foreach (array_unique($chemins) as $chemin) {
            MediaStorage::disk()->delete($chemin);
        }

        $this->info('Purge terminée.');
        return self::SUCCESS;
    }

    /** L'entité pointée par une relation polymorphe existe-t-elle encore ? */
    private function entiteExiste(string $type, int|string $id): bool
    {
        $classe = Relation::getMorphedModel($type) ?? $type;

        if (!class_exists($classe)) {
            return false;
        }

        return DB::table((new $classe)->getTable())->where('id', $id)->exists();
    }

    /** Fichiers des dossiers d'images qu'aucun média conservé ni aucune catégorie ne référence. */
    private function fichiersOrphelins($mediasOrphelins): array
    {
        $retires = $mediasOrphelins->flatMap(fn ($m) => [$m->chemin_fichier, $m->thumbnail])->filter();

        $references = Media::withTrashed()->get(['chemin_fichier', 'thumbnail'])
            ->flatMap(fn ($m) => [$m->chemin_fichier, $m->thumbnail])
            ->merge(DB::table('categories')->whereNotNull('image')->pluck('image'))
            ->filter()
            ->flip();

        $disque = MediaStorage::disk();
        $orphelins = [];

        foreach (self::DOSSIERS as $dossier) {
            foreach ($disque->allFiles($dossier) as $chemin) {
                if (!isset($references[$chemin])) {
                    $orphelins[] = $chemin;
                }
            }
        }

        // Les fichiers référencés par un média orphelin sont supprimés avec lui, on ne les recompte pas
        return array_values(array_diff($orphelins, $retires->all()));
    }
}
