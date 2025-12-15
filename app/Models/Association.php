<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Association extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_asso_ar',
        'nom_de_l_asso',
        'adresse',
        'jeagraphie',
        'date_de_creation',
        'tel',
        'remarque',
        'nombreBeneficiaire',
        'secteur_id',
        'districts_id',
        'email',
        'site_web',
        'statut_juridique',
        'numero_agrement',
        'date_agrement',
        'domaine_activite',
        'budget_annuel',
        'nombre_employes',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'date_de_creation' => 'date',
        'date_agrement' => 'date',
        'is_active' => 'boolean',
        'budget_annuel' => 'decimal:2',
        'nombre_employes' => 'integer',
        'nombreBeneficiaire' => 'integer',
    ];
 public function centres()
    {
        return $this->belongsToMany(Centre::class, 'association_centre');
    }
    /**
     * Get the secteur that owns the association
     */
    public function secteur(): BelongsTo
    {
        return $this->belongsTo(Secteur::class, 'secteur_id');
    }

    /**
     * Get the district that owns the association
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'districts_id');
    }

    /**
     * Get the user who created the association
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope active associations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by secteur
     */
    public function scopeBySecteur($query, $secteurId)
    {
        return $query->where('secteur_id', $secteurId);
    }

    /**
     * Scope by district
     */
    public function scopeByDistrict($query, $districtId)
    {
        return $query->where('districts_id', $districtId);
    }

    /**
     * Scope search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nom_asso_ar', 'like', "%{$search}%")
                    ->orWhere('nom_de_l_asso', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('numero_agrement', 'like', "%{$search}%");
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        return $this->adresse . ', ' . $this->jeagraphie;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

  // Relations basiques
    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }
        /**
         * Get the latest dossier by due_date
         */
        public function latestDossier()
        {
            return $this->hasOne(Dossier::class)->latestOfMany('due_date');
        }
    /**
     * Get the dossiers count
     */
    public function getDossiersCountAttribute()
    {
        return $this->dossiers()->count();
    }
}