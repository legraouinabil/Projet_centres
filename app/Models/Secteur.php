<?php
// app/Models/Secteur.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Secteur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_secteur_ar',
        'nom_secteur_fr',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get associations for this secteur
     */
    public function associations(): HasMany
    {
        return $this->hasMany(Association::class, 'secteur_id');
    }

    /**
     * Scope active secteurs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}