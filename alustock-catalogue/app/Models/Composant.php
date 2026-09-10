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
        'poids_lineaire_lbs_ft',
        'section_largeur_mm',
        'section_hauteur_mm',
        'epaisseur_paroi_mm',
        'moment_inertie_cm4',
        'perimetre_mm',
        'image_coupe',
        'est_disponible',
    ];

    protected $casts = [
        'est_disponible' => 'boolean',
        'poids_lineaire_kg_m' => 'decimal:3',
        'poids_lineaire_lbs_ft' => 'decimal:3',
        'section_largeur_mm' => 'decimal:2',
        'section_hauteur_mm' => 'decimal:2',
        'epaisseur_paroi_mm' => 'decimal:2',
        'moment_inertie_cm4' => 'decimal:2',
        'perimetre_mm' => 'decimal:2',
    ];

    // ============================================================
    // RELATIONS
    // ============================================================

    public function typeComposant(): BelongsTo
    {
        return $this->belongsTo(TypeComposant::class, 'type_composant_id');
    }

    public function gamme(): BelongsTo
    {
        return $this->belongsTo(Gamme::class, 'gamme_id');
    }

    public function ouvrages(): BelongsToMany
    {
        return $this->belongsToMany(Ouvrage::class, 'composition_ouvrage')
                    ->withPivot('quantite', 'unite', 'ordre', 'commentaire')
                    ->withTimestamps();
    }

    public function finitions(): BelongsToMany
    {
        return $this->belongsToMany(Finition::class, 'composant_finition')
                    ->withPivot('est_par_defaut')
                    ->withTimestamps();
    }

    public function caracteristiques()
    {
        return $this->morphMany(Caracteristique::class, 'caracterisable')
                    ->orderBy('ordre_affichage');
    }

    public function medias()
    {
        return $this->morphToMany(Media::class, 'mediable', 'media_morph')
                    ->withPivot('ordre')
                    ->withTimestamps()
                    ->orderBy('pivot_ordre');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_association')
                    ->withTimestamps();
    }

    public function scopeDisponible($query)
    {
        return $query->where('est_disponible', true);
    }

    public function scopeProfile($query)
    {
        return $query->whereHas('typeComposant', function ($q) {
            $q->where('slug', 'profile');
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getNomCompletAttribute(): string
    {
        return $this->reference . ' - ' . $this->designation;
    }

    public function isProfile(): bool
    {
        return $this->typeComposant?->slug === 'profile';
    }
}