<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $fillable = [
        'denomination',
        'domaine_intervention',
        'localisation',
        'superficie',
        'objectifs',
        'composantes',
        'nature_foncier',
        'cout_construction',
        'cout_equipement'
    ];

    protected $casts = [
        'domaine_intervention' => 'string',
    ];
  public function associations()
    {
        return $this->belongsToMany(Association::class, 'association_centre');
    }
    public function gestionnaire()
    {
        return $this->hasOne(Gestionnaire::class);
    }

    public function ressourcesHumaines()
    {
        return $this->hasMany(RessourceHumaine::class);
    }

    public function ressourcesFinancieres()
    {
        return $this->hasMany(RessourceFinanciere::class);
    }

    public function impactBeneficiaires()
    {
        return $this->hasMany(ImpactBeneficiaire::class);
    }


        public function ressourceFinancieres()
    {
        return $this->hasMany(RessourceFinanciere::class);
    }
    // Calcul de la masse salariale totale
    public function getMasseSalarialeAttribute()
    {
        return $this->ressourcesHumaines()->sum('salaire');
    }
}