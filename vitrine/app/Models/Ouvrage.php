<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ouvrage extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'description',
        'reference',
        'categorie_id',
        'date_realisation',
        'client',
        'localisation',
        'specifications',
        'is_active',
        'is_featured',
        'views',
        'meta_title',
        'meta_description',
        'seo_keywords'
    ];

    protected $casts = [
        'date_realisation' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'views' => 'integer'
    ];

    protected $appends = ['main_image_url'];

    // Relations
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function medias()
    {
        return $this->morphMany(Media::class, 'model')->ordered();
    }

    public function mainImage()
    {
        // Le OR est groupé : sans parenthèses, « orWhere('is_primary') » annulait le filtre
        // model_type / model_id et renvoyait l'image principale d'un AUTRE ouvrage.
        return $this->morphOne(Media::class, 'model')
            ->where(function ($q) {
                $q->where('collection_name', 'main')->orWhere('is_primary', true);
            })
            ->orderByDesc('is_primary')
            ->orderBy('order');
    }

    public function gallery()
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', 'gallery')
            ->ordered();
    }

    // Accesseurs
    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->mainImage;
        return $mainImage ? $mainImage->url : asset('images/placeholder-ouvrage.jpg');
    }

    public function getImagesAttribute()
    {
        return $this->gallery()->get();
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Méthodes
    public function getFormattedDateAttribute()
    {
        return $this->date_realisation ? $this->date_realisation->format('d/m/Y') : null;
    }

    // Auto-génération du slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ouvrage) {
            if (empty($ouvrage->slug)) {
                $ouvrage->slug = Str::slug($ouvrage->titre);
            }
        });

        static::updating(function ($ouvrage) {
            if ($ouvrage->isDirty('titre') && empty($ouvrage->slug)) {
                $ouvrage->slug = Str::slug($ouvrage->titre);
            }
        });
    }
}