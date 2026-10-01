<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Gestion des médias de la vitrine.
 *
 * - url()    : transforme un chemin en URL publique (ou null)
 * - urls()   : idem pour une liste (tableau, Collection, relation...)
 * - ouvrage(): normalise un modèle Ouvrage en tableau simple pour la vue
 * - demo()   : ouvrages fictifs pour travailler le design sans données
 *
 * Aucune vérification d'existence disque n'est faite ici : si le fichier
 * est absent, le navigateur échoue à le charger et <x-media-image> retombe
 * automatiquement sur le placeholder.
 */
class Media
{
    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        // URL absolue, protocole relatif ou chemin déjà public
        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::disk(config('vitrine.media.disk', 'public'))->url(ltrim($path, '/'));
    }

    /**
     * @param  mixed  $value  string | array | Collection | null
     * @return string[]
     */
    public static function urls($value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if ($value instanceof Collection) {
            $value = $value->all();
        }

        if (! is_array($value)) {
            $value = [$value];
        }

        $out = [];
        foreach ($value as $entry) {
            if (is_string($entry)) {
                $url = self::url($entry);
            } else {
                $url = null;
                foreach (['path', 'chemin', 'url', 'fichier', 'file', 'image', 'photo'] as $key) {
                    $candidate = self::get($entry, $key);
                    if (is_string($candidate) && $candidate !== '') {
                        $url = self::url($candidate);
                        break;
                    }
                }
            }

            if ($url) {
                $out[] = $url;
            }
        }

        return $out;
    }

    /**
     * Normalise un ouvrage (modèle Eloquent, tableau ou objet).
     * Les champs absents ne font jamais planter la vue.
     */
    public static function ouvrage($o, int $index = 0): array
    {
        $images = [];
        foreach (['images', 'photos', 'image', 'photo', 'image_principale', 'cover'] as $key) {
            $found = self::urls(self::get($o, $key));
            if ($found) {
                $images = $found;
                break;
            }
        }

        $categorie = self::get($o, 'categorie');
        $gamme = self::get($o, 'gamme');

        return [
            'id'          => self::get($o, 'id') ?? $index,
            'titre'       => (string) (self::get($o, 'titre') ?? self::get($o, 'nom') ?? 'Ouvrage sans titre'),
            'description' => (string) (self::get($o, 'description') ?? ''),
            'categorie'   => self::label($categorie),
            'gamme'       => self::label($gamme),
            'lieu'        => (string) (self::get($o, 'lieu') ?? ''),
            'annee'       => (string) (self::get($o, 'annee') ?? ''),
            'images'      => $images,
        ];
    }

    public static function demo(): array
    {
        $rows = [
            ['Façade vitrée — Centre d\'affaires', 'Façades', 'Gamme Structure', 'Lyon', '2024',
                'Mur-rideau en aluminium thermolaqué, grandes trames vitrées et finitions affleurantes.'],
            ['Verrière d\'atelier', 'Verrières', 'Gamme Design', 'Paris', '2023',
                'Verrière à profilés fins séparant l\'espace de travail tout en laissant passer la lumière.'],
            ['Garde-corps résidentiel', 'Garde-corps', 'Gamme 45', 'Nantes', '2024',
                'Garde-corps vitré sur mesure, fixation discrète et lignes épurées.'],
            ['Pergola bioclimatique', 'Extérieur', 'Gamme 55', 'Bordeaux', '2023',
                'Lames orientables motorisées pour moduler soleil et ventilation.'],
            ['Mur-rideau — Siège social', 'Façades', 'Gamme Structure', 'Lille', '2022',
                'Enveloppe vitrée haute performance thermique sur quatre niveaux.'],
            ['Escalier suspendu', 'Intérieur', 'Gamme Design', 'Marseille', '2024',
                'Limon central et marches en aluminium brossé, rampe filante.'],
        ];

        return array_map(fn ($r, $i) => [
            'id' => 'demo-' . $i, 'titre' => $r[0], 'categorie' => $r[1], 'gamme' => $r[2],
            'lieu' => $r[3], 'annee' => $r[4], 'description' => $r[5], 'images' => [],
        ], $rows, array_keys($rows));
    }

    // ------------------------------------------------------------------ privé

    private static function label($value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return (string) (self::get($value, 'nom') ?? self::get($value, 'titre') ?? '');
    }

    /** Lecture tolérante : ne plante pas en mode strict d'Eloquent. */
    private static function get($target, string $key)
    {
        if ($target === null) {
            return null;
        }

        try {
            return data_get($target, $key);
        } catch (Throwable $e) {
            return null;
        }
    }
}
