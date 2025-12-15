<?php
// app/Models/ImpactBeneficiaire.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactBeneficiaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id', 'domaine', 'intitule_filiere_discipline',
        'nombre_inscrits_hommes', 'nombre_inscrits_femmes',
        'heures_par_beneficiaire', 'nombre_abandons',
        'masse_salariale', 'charges_globales', 'annee'
    ];

    protected $casts = [
        'masse_salariale' => 'decimal:2',
        'charges_globales' => 'decimal:2',
        'annee' => 'integer'
    ];

    // Relations avec les tables spécifiques
    public function formationPro()
    {
        return $this->hasOne(ImpactFormationPro::class, 'impact_beneficiaire_id');
    }

    public function animation()
    {
        return $this->hasOne(ImpactAnimation::class, 'impact_beneficiaire_id');
    }

    public function handicap()
    {
        return $this->hasOne(ImpactHandicap::class, 'impact_beneficiaire_id');
    }

    public function eps()
    {
        return $this->hasOne(ImpactEps::class, 'impact_beneficiaire_id');
    }

    // Relation avec le centre
    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    // Relation avec les partenaires
    public function partenaires()
    {
        return $this->hasMany(Partenaire::class, 'impact_beneficiaire_id');
    }

    // Accessors
    public function getDomaineLabelAttribute()
    {
        return match($this->domaine) {
            'formation_pro' => 'Formation Professionnelle',
            'animation_culturelle_sportive' => 'Animation Culturelle & Sportive',
            'handicap' => 'Handicap',
            'eps' => 'EPS',
            default => $this->domaine
        };
    }

    public function getCoutRevientParBeneficiaireAttribute()
    {
        $totalBeneficiaires = $this->total_inscrits;
        return $totalBeneficiaires > 0 ? $this->charges_globales / $totalBeneficiaires : 0;
    }

    public function getTotalInscritsAttribute()
    {
        return $this->nombre_inscrits_hommes + $this->nombre_inscrits_femmes;
    }

    public function getTauxAbandonAttribute()
    {
        $totalInscrits = $this->total_inscrits;
        return $totalInscrits > 0 ? ($this->nombre_abandons / $totalInscrits) * 100 : 0;
    }

    // Scopes
    public function scopeByDomaine($query, $domaine)
    {
        return $query->where('domaine', $domaine);
    }

    public function scopeByAnnee($query, $annee)
    {
        return $query->where('annee', $annee);
    }

    public function scopeByCentre($query, $centreId)
    {
        return $query->where('centre_id', $centreId);
    }
}