<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourceFinanciere extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'budget_annee',
        'total_depenses',
        'total_recettes'
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    // Calcul du solde
    public function getSoldeAttribute()
    {
        return $this->total_recettes - $this->total_depenses;
    }
}