<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'centre_id',
        'association',
        'recepisse_definitif',
        'liste_membres',
        'liasse_fiscale'
    ];

    protected $casts = [
        'liste_membres' => 'array',
    ];

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }
}