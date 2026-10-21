<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Composant extends Model
{
    protected $table = 'composants';

    protected $fillable = [
        'reference', 'designation', 'slug', 'type_composant_id', 'matiere',
        'longueur_barre_mm', 'poids_lineaire_kg_m', 'poids_lineaire_lbs_ft',
        'section_largeur_mm', 'section_hauteur_mm', 'epaisseur_paroi_mm',
        'moment_inertie_cm4', 'perimetre_mm', 'image_coupe', 'est_disponible',
    ];

    protected $casts = [
        'est_disponible' => 'boolean',
        'poids_lineaire_kg_m' => 'decimal:3',
        'poids_lineaire_lbs_ft' => 'decimal:3',
        'section_largeur_mm' => 'decimal:2',
        'section_hauteur_mm' => 'decimal:2',
        'epaisseur_paroi_mm' => 'decimal:2',
        'moment_inertie_cm4' => 'decimal:3',
        'perimetre_mm' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $c) {
            if (empty($c->slug)) {
                $c->slug = self::uniqueSlug($c->reference, $c->designation);
            }
        });

        static::updating(function (self $c) {
            if ($c->isDirty(['reference', 'designation'])) {
                $c->slug = self::uniqueSlug($c->reference, $c->designation, $c->id);
            }
        });
    }

    protected static function uniqueSlug(string $reference, string $designation, ?int $ignoreId = null): string
    {
        $base = Str::slug($reference . '-' . $designation);
        $slug = $base;
        $i = 1;

        while (self::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    // ------------------------------------------------------------
    // Relations
    // ------------------------------------------------------------
    public function typeComposant(): BelongsTo
    {
        return $this->belongsTo(TypeComposant::class, 'type_composant_id');
    }

    public function ouvrages(): BelongsToMany
    {
        return $this->belongsToMany(Ouvrage::class, 'composition_ouvrage')
            ->withPivot(Ouvrage::PIVOT_COMPOSITION)
            ->withTimestamps();
    }

    public function finitions(): BelongsToMany
    {
        return $this->belongsToMany(Finition::class, 'composant_finition')
            ->withPivot('est_par_defaut')
            ->withTimestamps();
    }

    public function caracteristiques(): MorphMany
    {
        return $this->morphMany(Caracteristique::class, 'caracterisable')
            ->orderBy('ordre_affichage');
    }

    public function medias(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'mediable', 'media_morph')
            ->withPivot('ordre')
            ->withTimestamps()
            ->orderBy('media_morph.ordre');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_association')
            ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------
    public function scopeDisponible(Builder $query): Builder
    {
        return $query->where('est_disponible', true);
    }

    public function scopeProfile(Builder $query): Builder
    {
        return $query->whereHas('typeComposant', fn ($q) => $q->where('slug', 'profile'));
    }

    /** Pendant de Ouvrage::scopeRecherche — OR groupés. */
    public function scopeRecherche(Builder $query, ?string $terme, bool $complet = true): Builder
    {
        if ($terme === null || $terme === '') {
            return $query;
        }

        $like = '%' . addcslashes($terme, '%_\\') . '%';

        return $query->where(function (Builder $q) use ($like, $complet) {
            $q->where('designation', 'LIKE', $like)->orWhere('reference', 'LIKE', $like);
            if ($complet) {
                $q->orWhere('matiere', 'LIKE', $like);
            }
        });
    }

    // ------------------------------------------------------------
    // Accesseurs / helpers
    // ------------------------------------------------------------
    public function getNomCompletAttribute(): string
    {
        return $this->reference . ' - ' . $this->designation;
    }

    public function isProfile(): bool
    {
        return $this->typeComposant?->slug === 'profile';
    }
}
