<?php
// app/Models/ImpactFormationPro.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactFormationPro extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_beneficiaire_id', 'nombre_filieres', 'nombre_laureats',
        'taux_insertion_professionnelle', 'moyenne_premier_salaire',
        'nombre_inscrits_1ere_annee', 'nombre_inscrits_2eme_annee'
    ];

    protected $casts = [
        'taux_insertion_professionnelle' => 'decimal:2',
        'moyenne_premier_salaire' => 'decimal:2'
    ];

    public function impactBeneficiaire()
    {
        return $this->belongsTo(ImpactBeneficiaire::class, 'impact_beneficiaire_id');
    }

    // Accessors
    public function getTauxReussiteAttribute()
    {
        $totalInscrits = $this->nombre_inscrits_1ere_annee + $this->nombre_inscrits_2eme_annee;
        return $totalInscrits > 0 ? ($this->nombre_laureats / $totalInscrits) * 100 : 0;
    }
}