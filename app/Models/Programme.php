<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function associations(): BelongsToMany
    {
        return $this->belongsToMany(Association::class, 'association_programme');
    }
}
