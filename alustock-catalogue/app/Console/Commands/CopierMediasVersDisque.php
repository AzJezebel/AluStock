<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Envoie les images déjà présentes dans storage/app/public vers un autre disque (bucket S3, R2…).
 * À lancer UNE fois depuis le poste de dev, avec les identifiants du bucket dans le .env local.
 * Les fichiers déjà présents sur la destination (même chemin) sont ignorés : on peut relancer sans risque.
 */
class CopierMediasVersDisque extends Command
{
    protected $signature = 'media:copier-vers {disque : Nom du disque de destination (ex. s3)}
                            {--source=public : Disque source}
                            {--dry-run : Liste ce qui serait copié, sans rien envoyer}';

    protected $description = 'Copie les images du catalogue vers un autre disque (bucket)';

    public function handle(): int
    {
        $source = Storage::disk($this->option('source'));
        $cible  = Storage::disk($this->argument('disque'));
        $dryRun = (bool) $this->option('dry-run');

        $copies = $ignores = $erreurs = 0;

        foreach ($source->allFiles('images') as $chemin) {
            if ($cible->exists($chemin)) {
                $ignores++;
                continue;
            }

            if ($dryRun) {
                $this->line("à copier : {$chemin}");
                $copies++;
                continue;
            }

            $flux = $source->readStream($chemin);

            if ($flux !== null && $cible->writeStream($chemin, $flux)) {
                $copies++;
            } else {
                $erreurs++;
                $this->error("échec : {$chemin}");
            }

            if (is_resource($flux)) {
                fclose($flux);
            }
        }

        $this->info(($dryRun ? 'À copier' : 'Copiés') . " : {$copies} · déjà présents : {$ignores} · erreurs : {$erreurs}");

        return $erreurs > 0 ? self::FAILURE : self::SUCCESS;
    }
}
