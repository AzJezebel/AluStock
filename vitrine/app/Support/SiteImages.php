<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Images de la vitrine sans base de données.
 *
 * Les fichiers sont rangés sur le disque configuré (config('vitrine.media.disk'))
 * et le lien emplacement -> images est gardé dans storage/app/vitrine-media.json :
 *
 *   { "hero":  [ {"path": "vitrine/hero/a.jpg", "alt": ""}, ... ],
 *     "about": [ {"path": "vitrine/about/b.jpg", "alt": ""} ] }
 *
 * Emplacements : 'hero' (plusieurs, ordonnés) et 'about' (une seule, remplacée à chaque ajout).
 */
class SiteImages
{
    public const SLOTS = [
        'hero'  => ['multiple' => true],
        'about' => ['multiple' => false],
    ];

    private static ?array $cache = null;

    // ------------------------------------------------------------- lecture

    /** @return array<int, array{path:string, alt:string, url:string}> */
    public static function get(string $slot): array
    {
        self::assertSlot($slot);

        return collect(self::load()[$slot] ?? [])->map(fn ($i) => [
            'path' => $i['path'],
            'alt'  => $i['alt'] ?? '',
            'url'  => Media::url($i['path']),
        ])->values()->all();
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
        $data = self::load();

        if (! self::SLOTS[$slot]['multiple']) {
            foreach ($data[$slot] ?? [] as $old) {
                Storage::disk(self::disk())->delete($old['path']);
            }
            $data[$slot] = [];
        }

        $data[$slot][] = ['path' => $path, 'alt' => $alt];
        self::save($data);

        return ['path' => $path, 'alt' => $alt, 'url' => Media::url($path)];
    }

    /** Retire l'image de l'emplacement et supprime le fichier. */
    public static function remove(string $slot, string $path): void
    {
        self::assertSlot($slot);
        $data = self::load();

        $kept = array_values(array_filter($data[$slot] ?? [], fn ($i) => $i['path'] !== $path));
        if (count($kept) === count($data[$slot] ?? [])) {
            return; // chemin inconnu : on ne touche à aucun fichier
        }

        Storage::disk(self::disk())->delete($path);
        $data[$slot] = $kept;
        self::save($data);
    }

    /** Réordonne selon la liste de chemins reçue (les chemins inconnus sont ignorés). */
    public static function reorder(string $slot, array $paths): void
    {
        self::assertSlot($slot);
        $data = self::load();
        $byPath = collect($data[$slot] ?? [])->keyBy('path');

        $ordered = [];
        foreach ($paths as $p) {
            if ($byPath->has($p)) {
                $ordered[] = $byPath->pull($p);
            }
        }
        // ce qui n'était pas dans la liste reste à la fin
        $data[$slot] = array_merge($ordered, $byPath->values()->all());
        self::save($data);
    }

    public static function setAlt(string $slot, string $path, string $alt): void
    {
        self::assertSlot($slot);
        $data = self::load();
        foreach ($data[$slot] ?? [] as $k => $i) {
            if ($i['path'] === $path) {
                $data[$slot][$k]['alt'] = $alt;
            }
        }
        self::save($data);
    }

    // ------------------------------------------------------------- interne

    private static function disk(): string
    {
        return config('vitrine.media.disk', 'public');
    }

    private static function file(): string
    {
        return storage_path('app/vitrine-media.json');
    }

    private static function load(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $raw = is_file(self::file()) ? file_get_contents(self::file()) : '';
        $data = json_decode($raw ?: '[]', true);

        return self::$cache = is_array($data) ? $data : [];
    }

    private static function save(array $data): void
    {
        // écriture atomique : fichier temporaire puis rename
        $tmp = self::file() . '.' . uniqid('', true) . '.tmp';
        file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), LOCK_EX);
        rename($tmp, self::file());
        self::$cache = $data;
    }

    private static function assertSlot(string $slot): void
    {
        if (! isset(self::SLOTS[$slot])) {
            throw new \InvalidArgumentException("Emplacement inconnu : {$slot}");
        }
    }
}