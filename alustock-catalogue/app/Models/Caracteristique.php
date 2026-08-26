<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Caracteristique extends Model
{
    protected $table = 'caracteristiques';
    protected $fillable = [
        'caracterisable_id',
        'caracterisable_type',
        'cle',
        'valeur',
        'unite',
        'ordre_affichage',
    ];

    public function caracterisable(): MorphTo
    {
        return $this->morphTo();
    }
}