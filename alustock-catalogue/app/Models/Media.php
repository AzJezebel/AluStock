<?php

// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use SoftDeletes;

    protected $table = 'medias';

    protected $fillable = [
        'chemin_fichier',
        'thumbnail',
        'titre',
        'alt_text',
        'description',
        'type_media',
        'taille_octets',
        'mime_type',
        'largeur_px',
        'hauteur_px',
        'est_principal',
    ];

    protected $casts = [
        'est_principal' => 'boolean',
        'taille_octets' => 'integer',
        'largeur_px' => 'integer',
        'hauteur_px' => 'integer',
    ];

    public function mediable()
    {
        return $this->morphToMany(
            Model::class,
            'mediable',
            'media_morph'
        )->withPivot('ordre');
    }

    // Relations directes (pour faciliter les requêtes)
    public function ouvrages(): MorphToMany
    {
        return $this->morphedByMany(Ouvrage::class, 'mediable', 'media_morph')
                    ->withPivot('ordre')
                    ->withTimestamps();
    }

    public function composants(): MorphToMany
    {
        return $this->morphedByMany(Composant::class, 'mediable', 'media_morph')
                    ->withPivot('ordre')
                    ->withTimestamps();
    }

    public function gammes(): MorphToMany
    {
        return $this->morphedByMany(Gamme::class, 'mediable', 'media_morph')
                    ->withPivot('ordre')
                    ->withTimestamps();
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(Categorie::class, 'mediable', 'media_morph')
                    ->withPivot('ordre')
                    ->withTimestamps();
    }

    public function getUrlAttribute(): string
    {
        return $this->chemin_fichier 
            ? asset('storage/' . $this->chemin_fichier)
            : '';
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        // Fallback sur l'image principale
        return $this->url;
    }

    public function getTailleFormattedAttribute(): string
    {
        if (!$this->taille_octets) {
            return 'N/A';
        }
        
        $bytes = $this->taille_octets;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function scopePrincipal($query)
    {
        return $query->where('est_principal', true);
    }

    public function scopeSchema($query)
    {
        return $query->where('type_media', 'schema');
    }

    public function scopePhoto($query)
    {
        return $query->where('type_media', 'photo');
    }

    public function scopeRendu3d($query)
    {
        return $query->where('type_media', 'rendu_3d');
    }
}