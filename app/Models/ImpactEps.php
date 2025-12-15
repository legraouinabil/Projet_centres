<?php
// app/Models/ImpactEps.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactEps extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_beneficiaire_id', 'nombre_handicaps_traites',
        'heures_medecin_an', 'heures_assistant_social_an',
        'heures_psychologue_an'
    ];

    public function impactBeneficiaire()
    {
        return $this->belongsTo(ImpactBeneficiaire::class, 'impact_beneficiaire_id');
    }

    // Accessors
    public function getTotalHeuresAccompagnementAttribute()
    {
        return $this->heures_medecin_an + 
               $this->heures_assistant_social_an + 
               $this->heures_psychologue_an;
    }
}