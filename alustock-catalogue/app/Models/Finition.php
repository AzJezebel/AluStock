<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Finition extends Model
{
    protected $table = 'finitions';
    protected $fillable = [
        'nom',
        'slug',
        'code_ral',
        'type_finition',
        'description',
    ];

    public function composants(): BelongsToMany
    {
        return $this->belongsToMany(Composant::class, 'composant_finition')
            ->withPivot('est_par_defaut');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}