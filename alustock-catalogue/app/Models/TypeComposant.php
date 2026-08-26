<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeComposant extends Model
{
    protected $table = 'types_composant';
    protected $fillable = [
        'nom',
        'slug',
        'description',
    ];

    public function composants(): HasMany
    {
        return $this->hasMany(Composant::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}