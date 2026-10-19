<?php

namespace App\Support;

use App\Models\SiteImage;
use Illuminate\Http\UploadedFile;

/**
 * Images de la vitrine (hero + « À propos »).
 *
 * Le lien emplacement → image est dans la table `site_images` (modèle SiteImage) ;
 * les fichiers sont rangés sur le disque config('vitrine.media.disk') dans vitrine/{emplacement}/.
 *
 * Emplacements : 'hero' (plusieurs, ordonnés) et 'about' (une seule, remplacée à chaque ajout).
 * L'API (get, urls, first, add, remove, reorder, setAlt) est la même qu'avant : contrôleur et vues inchangés.
 */
class SiteImages
{
    public const SLOTS = [
        'hero'  => ['multiple' => true],
        'about' => ['multiple' => false],
    ];

    /** @var array<string, array<int, array{path:string, alt:string, url:string}>> mémo par requête */
    private static array $cache = [];

    // ------------------------------------------------------------- lecture

    /** @return array<int, array{path:string, alt:string, url:string}> */
    public static function get(string $slot): array
    {
        self::assertSlot($slot);

        return self::$cache[$slot] ??= SiteImage::slot($slot)->get()->map(fn (SiteImage $i) => [
            'path' => $i->file_path,
            'alt'  => $i->alt,
            'url'  => VitrineMedia::url($i->file_path),
        ])->all();
    }

    /** @return string[] URLs publiques, dans l'ordre */
    public static function urls(string $slot): array
    {
        return array_column(self::get($slot), 'url');
    }

    public static function first(string $slot): ?string
    {
        return self::urls($slot)[0] ?? null;
    }

    // ------------------------------------------------------------- écriture

    /** Ajoute une image (remplace l'existante pour un emplacement « single »). */
    public static function add(string $slot, UploadedFile $file, string $alt = ''): array
    {
        self::assertSlot($slot);

        $path = $file->store('vitrine/' . $slot, self::disk());

        if (! self::SLOTS[$slot]['multiple']) {
            SiteImage::slot($slot)->get()->each->delete(); // supprime lignes + anciens fichiers
        }

        SiteImage::create([
            'slot'      => $slot,
            'file_path' => $path,
            'alt'       => $alt,
            'order'     => (int) SiteImage::where('slot', $slot)->max('order') + 1,
        ]);

        self::forget($slot);

        return ['path' => $path, 'alt' => $alt, 'url' => VitrineMedia::url($path)];
    }

    /** Retire l'image de l'emplacement et supprime le fichier. */
    public static function remove(string $slot, string $path): void
    {
        self::assertSlot($slot);

        // chemin inconnu pour cet emplacement : on ne touche à aucun fichier
        SiteImage::where('slot', $slot)->where('file_path', $path)->get()->each->delete();

        self::forget($slot);
    }

    /** Réordonne selon la liste de chemins reçue (les chemins inconnus sont ignorés, le reste passe à la fin). */
    public static function reorder(string $slot, array $paths): void
    {
        self::assertSlot($slot);

        $images = SiteImage::slot($slot)->get()->keyBy('file_path');

        $position = 0;
        foreach ($paths as $path) {
            if ($image = $images->pull($path)) {
                $image->update(['order' => $position++]);
            }
        }
        foreach ($images as $image) { // ce qui n'était pas dans la liste reste à la fin
            $image->update(['order' => $position++]);
        }

        self::forget($slot);
    }

    public static function setAlt(string $slot, string $path, string $alt): void
    {
        self::assertSlot($slot);

        SiteImage::where('slot', $slot)->where('file_path', $path)->update(['alt' => $alt]);

        self::forget($slot);
    }

    // ------------------------------------------------------------- interne

    private static function disk(): string
    {
        return config('vitrine.media.disk', 'public');
    }

    private static function forget(string $slot): void
    {
        unset(self::$cache[$slot]);
    }

    private static function assertSlot(string $slot): void
    {
        if (! isset(self::SLOTS[$slot])) {
            throw new \InvalidArgumentException("Emplacement inconnu : {$slot}");
        }
    }
}