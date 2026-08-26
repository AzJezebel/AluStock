<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Media extends Model
{
    protected $table = 'medias';
    protected $fillable = [
        'chemin_fichier',
        'titre',
        'description',
        'type_media',
        'est_principal',
    ];

    protected $casts = [
        'est_principal' => 'boolean',
    ];

    public function mediable(): MorphToMany
    {
        return $this->morphToMany(
            Ouvrage::class, // ou Gamme, Categorie, Composant
            'mediable',
            'media_morph'
        )->withPivot('ordre');
    }
}