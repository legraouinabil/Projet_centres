<?php
// app/Models/District.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_district_ar',
        'nom_district_fr',
        'region',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get associations for this district
     */
    public function associations(): HasMany
    {
        return $this->hasMany(Association::class, 'districts_id');
    }

    /**
     * Scope active districts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}