<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourceHumaine extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'poste',
        'nom_prenom',
        'salaire',
        'type_contrat'
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
}