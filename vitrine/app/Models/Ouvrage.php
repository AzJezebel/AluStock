<?php
// app/Models/Ouvrage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ouvrage extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'description',
        'reference',
        'categorie_id',
        'date_realisation',
        'client',
        'localisation',
        'specifications',
        'is_active',
        'is_featured',
        'views',
    ];

    protected $casts = [
        'date_realisation' => 'date',
        'is_active'        => 'boolean',
        'is_featured'      => 'boolean',
        'views'            => 'integer',
    ];

    // Relations
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function medias()
    {
        return $this->morphMany(Media::class, 'model')->ordered();
    }

    /** Image de couverture : le média marqué is_primary. */
    public function mainImage()
    {
        return $this->morphOne(Media::class, 'model')
            ->where('is_primary', true)
            ->orderBy('order');
    }

    /** Images supplémentaires : les médias non principaux. */
    public function gallery()
    {
        return $this->morphMany(Media::class, 'model')
            ->where('is_primary', false)
            ->ordered();
    }

    // Accesseurs
    public function getFormattedDateAttribute()
    {
        return $this->date_realisation?->format('d/m/Y');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Slug unique + nettoyage des fichiers à la suppression
    protected static function booted(): void
    {
        static::creating(function (Ouvrage $ouvrage) {
            if (empty($ouvrage->slug)) {
                $ouvrage->slug = static::uniqueSlug($ouvrage->titre);
            }
        });

        static::updating(function (Ouvrage $ouvrage) {
            if ($ouvrage->isDirty('titre') && ! $ouvrage->isDirty('slug')) {
                $ouvrage->slug = static::uniqueSlug($ouvrage->titre, $ouvrage->id);
            }
        });

        // Chaque Media supprimé efface aussi son fichier (voir Media::booted)
        static::deleting(function (Ouvrage $ouvrage) {
            $ouvrage->medias()->get()->each->delete();
        });
    }

    public static function uniqueSlug(string $titre, ?int $ignoreId = null): string
    {
        $base = Str::slug($titre) ?: 'ouvrage';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}