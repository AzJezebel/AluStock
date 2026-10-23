<?php

namespace App\Support;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

/**
 * Point d'entrée unique pour les fichiers du catalogue (images d'ouvrages, de composants, de catégories).
 *
 * Le disque se choisit avec MEDIA_DISK dans .env :
 *   - "public" (défaut) : storage/app/public, servi via le lien public/storage (développement, hébergement classique)
 *   - un disque S3 (ex. "s3") : bucket de stockage d'objets (Laravel Cloud, S3, R2…)
 *
 * Le reste du code ne connaît plus le nom du disque : il passe par disk() et url().
 */
class MediaStorage
{
    public static function diskName(): string
    {
        return config('filesystems.media_disk', 'public');
    }

    public static function disk(): FilesystemAdapter
    {
        return Storage::disk(self::diskName());
    }

    /**
     * URL publique d'un chemin relatif (ex. "images/ouvrages/xxx.jpg").
     */
    public static function url(?string $chemin): ?string
    {
        if (!$chemin) {
            return null;
        }

        // Disque local : on garde asset(), qui suit l'hôte de la requête (127.0.0.1, domaine…)
        // et ne dépend pas de APP_URL.
        if (config('filesystems.disks.' . self::diskName() . '.driver') === 'local') {
            return asset('storage/' . ltrim($chemin, '/'));
        }

        return self::disk()->url($chemin);
    }
}
