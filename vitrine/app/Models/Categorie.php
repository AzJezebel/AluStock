<?php
// app/Models/Categorie.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'slug',
        'description',
        'icone',
        'is_active',
        'ordre',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'ordre'     => 'integer',
    ];

    // Relations
    public function ouvrages()
    {
        return $this->hasMany(Ouvrage::class);
    }

    // Scopes (mêmes noms que sur Ouvrage : actif())
    public function scopeActif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }

    // Slug unique généré automatiquement (identique à la logique d'Ouvrage)
    protected static function booted(): void
    {
        static::creating(function (Categorie $categorie) {
            if (empty($categorie->slug)) {
                $categorie->slug = static::uniqueSlug($categorie->nom);
            }
        });

        static::updating(function (Categorie $categorie) {
            if ($categorie->isDirty('nom') && ! $categorie->isDirty('slug')) {
                $categorie->slug = static::uniqueSlug($categorie->nom, $categorie->id);
            }
        });
    }

    public static function uniqueSlug(string $nom, ?int $ignoreId = null): string
    {
        $base = Str::slug($nom) ?: 'categorie';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}