<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gamme extends Model
{
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'image_cover',
        'ordre_affichage',
    ];

    public function ouvrages(): HasMany
    {
        return $this->hasMany(Ouvrage::class);
    }

    public function composants(): HasMany
    {
        return $this->hasMany(Composant::class);
    }

    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}