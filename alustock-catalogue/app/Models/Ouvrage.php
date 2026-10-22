<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Ouvrage extends Model
{
    protected $table = 'ouvrages';

    protected $fillable = [
        'reference', 'nom', 'slug', 'categorie_id',
        'description_courte', 'description_technique',
        'largeur_min_mm', 'largeur_max_mm', 'hauteur_min_mm', 'hauteur_max_mm',
        'performance_thermique', 'performance_acoustique',
        'image_principale', 'est_actif',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
    ];

    /** Colonnes du pivot composition_ouvrage (une seule définition, partagée avec Composant). */
    public const PIVOT_COMPOSITION = ['quantite', 'unite', 'ordre', 'commentaire'];

    // ------------------------------------------------------------
    // Slug : même logique unique que Composant (suffixe -1, -2…)
    // ------------------------------------------------------------
    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $o) {
            if (empty($o->slug)) {
                $o->slug = self::uniqueSlug($o->reference, $o->nom);
            }
        });

        static::updating(function (self $o) {
            if ($o->isDirty(['reference', 'nom'])) {
                $o->slug = self::uniqueSlug($o->reference, $o->nom, $o->id);
            }
        });
    }

    protected static function uniqueSlug(string $reference, string $nom, ?int $ignoreId = null): string
    {
        $base = Str::slug($reference . '-' . $nom);
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
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function composants(): BelongsToMany
    {
        return $this->belongsToMany(Composant::class, 'composition_ouvrage')
            ->withPivot(self::PIVOT_COMPOSITION)
            ->withTimestamps()
            ->orderBy('composition_ouvrage.ordre');
    }

    public function medias(): MorphToMany
    {
        // Même définition que Composant::medias()
        return $this->morphToMany(Media::class, 'mediable', 'media_morph')
            ->withPivot('ordre')
            ->withTimestamps()
            ->orderBy('media_morph.ordre');
    }

    public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'documentable', 'document_morph')
            ->withPivot('ordre')
            ->withTimestamps()
            ->orderBy('document_morph.ordre');
    }

    public function caracteristiques(): MorphMany
    {
        return $this->morphMany(Caracteristique::class, 'caracterisable')
            ->orderBy('ordre_affichage');
    }

    /**
     * Référence normalisée à l'enregistrement : espaces superflus retirés, majuscules.
     * Évite "aln-877 " et "ALN-877" comme deux références distinctes.
     */
    protected function reference(): Attribute
    {
        return Attribute::make(
            set: fn (string $valeur) => mb_strtoupper(trim(preg_replace('/\s+/', ' ', $valeur))),
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ------------------------------------------------------------
    // Scopes
    // ------------------------------------------------------------
    public function scopeActif(Builder $query): Builder
    {
        return $query->where('est_actif', true);
    }

    /** Recherche texte unique (index, search, autocomplete) — les OR sont groupés. */
    public function scopeRecherche(Builder $query, ?string $terme, bool $complet = true): Builder
    {
        if ($terme === null || $terme === '') {
            return $query;
        }

        $like = '%' . addcslashes($terme, '%_\\') . '%';

        return $query->where(function (Builder $q) use ($like, $complet) {
            $q->where('nom', 'LIKE', $like)->orWhere('reference', 'LIKE', $like);
            if ($complet) {
                $q->orWhere('description_courte', 'LIKE', $like)
                  ->orWhere('description_technique', 'LIKE', $like);
            }
        });
    }
}
