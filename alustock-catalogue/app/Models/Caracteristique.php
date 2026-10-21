<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

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

    /**
     * La clé est toujours stockée en snake_case sans accent (ex. "type_ouverture").
     * "Joint", "Type d'ouverture" ou "type ouverture" sont normalisés à l'enregistrement.
     */
    protected function cle(): Attribute
    {
        return Attribute::make(
            set: fn (string $valeur) => Str::slug($valeur, '_'),
        );
    }

    // Libellé d'affichage : "type_ouverture" => "Type ouverture"
    public function getLibelleAttribute(): string
    {
        return Str::ucfirst(str_replace('_', ' ', $this->cle));
    }
}
