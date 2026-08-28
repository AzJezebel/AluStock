<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gamme extends Model
{
    protected $table = 'gammes';
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
    
    // Comptage des composants via les ouvrages de la gamme
    public function getComposantsCountAttribute()
    {
        return $this->ouvrages()->withCount('composants')->get()->sum('composants_count');
    }

    public function medias()
    {
        return $this->morphToMany(
            Media::class,
            'mediable',
            'media_morph',
            'mediable_id',
            'media_id'
        )->withPivot('ordre')
         ->orderBy('pivot_ordre');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}