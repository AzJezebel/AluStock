<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ouvrage extends Model
{
    protected $table = 'ouvrages';
    protected $fillable = [
        'reference',
        'nom',
        'slug',
        'gamme_id',
        'categorie_id',
        'description_courte',
        'description_technique',
        'largeur_min_mm',
        'largeur_max_mm',
        'hauteur_min_mm',
        'hauteur_max_mm',
        'performance_thermique',
        'performance_acoustique',
        'image_principale',
        'est_actif',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
    ];

    // Relations
    public function gamme(): BelongsTo
    {
        return $this->belongsTo(Gamme::class);
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function composants(): BelongsToMany
    {
        return $this->belongsToMany(Composant::class, 'composition_ouvrage')
            ->withPivot('quantite', 'unite', 'ordre', 'longueur_coupe_mm', 'commentaire')
            ->orderBy('pivot_ordre');
    }

    public function medias()
    {
        return $this->morphToMany(
            Media::class,          // Modèle cible
            'mediable',            // Nom de la relation polymorphique
            'media_morph',         // Nom de la table pivot
            'mediable_id',         // Clé étrangère sur l'entité cible (dans media_morph)
            'media_id'             // Clé étrangère sur le média (dans media_morph)
        )->withPivot('ordre')      // Attribut supplémentaire dans la table pivot
         ->orderBy('pivot_ordre'); // Ordre par la colonne pivot
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

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('est_actif', true);
    }
}