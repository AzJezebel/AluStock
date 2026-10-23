<?php

namespace App\Models;

use App\Support\MediaStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'nom',
        'slug',
        'description',
        'image',
        'ordre',
    ];

    /**
     * Ordre d'affichage choisi dans l'admin ; à égalité, ordre alphabétique.
     */
    public function scopeOrdonnees(Builder $query): Builder
    {
        return $query->orderBy('ordre')->orderBy('nom');
    }

    public function ouvrages(): HasMany
    {
        return $this->hasMany(Ouvrage::class);
    }

    /**
     * Ajoute `ouvrages_count`, `composants_count` (composants distincts utilisés par
     * les ouvrages de la catégorie) et `references_count` (la somme des deux, même
     * logique que le compteur "N=… REFS" du header) en une seule requête.
     */
    public function scopeWithReferencesCount(Builder $query): Builder
    {
        return $query
            ->withCount('ouvrages')
            ->addSelect(['composants_count' => DB::table('composition_ouvrage as co')
                ->join('ouvrages as o', 'o.id', '=', 'co.ouvrage_id')
                ->whereColumn('o.categorie_id', 'categories.id')
                ->selectRaw('COUNT(DISTINCT co.composant_id)')]);
    }

    public function getReferencesCountAttribute(): int
    {
        return (int) ($this->attributes['ouvrages_count'] ?? 0)
             + (int) ($this->attributes['composants_count'] ?? 0);
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
         ->orderBy('media_morph.ordre');
    }

    // URL publique de la miniature (null si aucune image)
    public function getImageUrlAttribute(): ?string
    {
        return MediaStorage::url($this->image);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}