<?php
// app/Models/ImpactHandicap.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactHandicap extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_beneficiaire_id', 'nombre_handicaps_traites',
        'heures_medecin_an', 'heures_assistant_social_an',
        'heures_orthophoniste_an', 'heures_kinesitherapie_an',
        'heures_psychologue_an'
    ];

    public function impactBeneficiaire()
    {
        return $this->belongsTo(ImpactBeneficiaire::class, 'impact_beneficiaire_id');
    }

    // Accessors
    public function getTotalHeuresSoinsAttribute()
    {
        return $this->heures_medecin_an + 
               $this->heures_assistant_social_an + 
               $this->heures_orthophoniste_an + 
               $this->heures_kinesitherapie_an + 
               $this->heures_psychologue_an;
    }

    public function getHeuresMoyennesParBeneficiaireAttribute()
    {
        $impact = $this->impactBeneficiaire;
        $totalBeneficiaires = $impact->nombre_inscrits_hommes + $impact->nombre_inscrits_femmes;
        
        return $totalBeneficiaires > 0 ? $this->total_heures_soins / $totalBeneficiaires : 0;
    }
}