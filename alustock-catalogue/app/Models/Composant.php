<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Composant extends Model
{
    protected $table = 'composants';
    protected $fillable = [
        'reference',
        'designation',
        'slug',
        'type_composant_id',
        'gamme_id',
        'matiere',
        'longueur_barre_mm',
        'poids_lineaire_kg_m',
        'section_largeur_mm',
        'section_hauteur_mm',
        'epaisseur_paroi_mm',
        'moment_inertie_x_cm4',
        'moment_inertie_y_cm4',
        'module_elasticite_x_cm3',
        'module_elasticite_y_cm3',
        'image_coupe',
        'est_disponible',
    ];

    protected $casts = [
        'est_disponible' => 'boolean',
    ];

    // Relations
    public function typeComposant(): BelongsTo
    {
        return $this->belongsTo(TypeComposant::class);
    }

    public function gamme(): BelongsTo
    {
        return $this->belongsTo(Gamme::class);
    }

    public function ouvrages(): BelongsToMany
    {
        return $this->belongsToMany(Ouvrage::class, 'composition_ouvrage')
            ->withPivot('quantite', 'unite', 'ordre', 'longueur_coupe_mm', 'commentaire');
    }

    public function finitions(): BelongsToMany
    {
        return $this->belongsToMany(Finition::class, 'composant_finition')
            ->withPivot('est_par_defaut');
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

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_association');
    }

    public function caracteristiques()
    {
        return $this->morphMany(Caracteristique::class, 'caracterisable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeDisponible($query)
    {
        return $query->where('est_disponible', true);
    }

    public function getNomCompletAttribute()
    {
        return $this->reference . ' - ' . $this->designation;
    }
}