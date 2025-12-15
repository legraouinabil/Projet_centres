<?php
// app/Models/Dossier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dossier extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'reference',
        'status',
        'assigned_to',
        'due_date',
        'association_id', // Ajouter ce champ
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the association that owns the dossier
     */
    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class, 'association_id');
    }

    /**
     * Get the documents for the dossier
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Get the user who created the dossier
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the assigned user
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope for active dossiers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for association dossiers
     */
    public function scopeByAssociation($query, $associationId)
    {
        return $query->where('association_id', $associationId);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'active' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'archived' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'Brouillon',
            'active' => 'Actif',
            'completed' => 'Terminé',
            'archived' => 'Archivé',
            default => 'Inconnu',
        };
    }
}