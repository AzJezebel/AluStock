<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'icone',
    ];

    public function ouvrages(): HasMany
    {
        return $this->hasMany(Ouvrage::class);
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