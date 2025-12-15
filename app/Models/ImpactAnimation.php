<?php
// app/Models/ImpactAnimation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactAnimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_beneficiaire_id', 'nombre_disciplines',
        'nombre_inscrits_ecoles', 'nombre_inscrits_particuliers',
        'nombre_conventions', 'nombre_evenements_organises',
        'nombre_participations_competitions', 'nombre_trophees_gagnes'
    ];

    public function impactBeneficiaire()
    {
        return $this->belongsTo(ImpactBeneficiaire::class, 'impact_beneficiaire_id');
    }

    // Accessors
    public function getTotalInscritsAttribute()
    {
        return $this->nombre_inscrits_ecoles + $this->nombre_inscrits_particuliers;
    }

    public function getTauxReussiteAttribute()
    {
        return $this->nombre_participations_competitions > 0 ? 
               ($this->nombre_trophees_gagnes / $this->nombre_participations_competitions) * 100 : 0;
    }
}