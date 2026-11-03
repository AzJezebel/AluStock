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
        $target  = Storage::disk($this->argument('disque'));
        $dryRun = (bool) $this->option('dry-run');

        $copies = $ignored = $errors = 0;

        foreach ($source->allFiles('images') as $path) {
            if ($target->exists($path)) {
                $ignored++;
                continue;
            }

            if ($dryRun) {
                $this->line("à copier : {$path}");
                $copies++;
                continue;
            }

            $stream = $source->readStream($path);

            if ($stream !== null && $target->writeStream($path, $stream)) {
                $copies++;
            } else {
                $errors++;
                $this->error("échec : {$path}");
            }

            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $this->info(($dryRun ? 'À copier' : 'Copiés') . " : {$copies} · déjà présents : {$ignored} · erreurs : {$errors}");

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
