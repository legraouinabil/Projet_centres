<?php
// app/Models/Partenaire.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'impact_beneficiaire_id', 'nom', 'type',
        'contact_nom', 'contact_telephone', 'contact_email'
    ];

    public function impactBeneficiaire()
    {
        return $this->belongsTo(ImpactBeneficiaire::class, 'impact_beneficiaire_id');
    }

    // Accessors
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'institutionnel' => 'Institutionnel',
            'entreprise' => 'Entreprise',
            'ong' => 'ONG',
            'association' => 'Association',
            'autre' => 'Autre',
            default => $this->type
        };
    }
    
}