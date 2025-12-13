<?php
// app/Http/Livewire/AssociationManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Association;
use App\Models\Secteur;
use App\Models\District;
use Illuminate\Support\Facades\Auth;

class AssociationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $secteurFilter = '';
    public $districtFilter = '';
    public $statusFilter = '';
    public $showAssociationModal = false;
    public $showDeleteModal = false;
    
    // Association form fields
    public $associationId;
    public $nom_asso_ar = '';
    public $nom_de_l_asso = '';
    public $adresse = '';
    public $jeagraphie = '';
    public $date_de_creation = '';
    public $tel = '';
    public $remarque = '';
    public $nombreBeneficiaire = 0;
    public $email = '';
    public $site_web = '';
    public $statut_juridique = '';
    public $numero_agrement = '';
    public $date_agrement = '';
    public $domaine_activite = '';
    public $budget_annuel = 0;
    public $nombre_employes = 0;
    public $secteur_id = '';
    public $districts_id = '';
    public $is_active = true;

    protected $rules = [
        'nom_asso_ar' => 'required|string|max:255',
        'nom_de_l_asso' => 'required|string|max:255',
        'adresse' => 'required|string|max:500',
        'jeagraphie' => 'required|string|max:255',
        'date_de_creation' => 'required|date',
        'tel' => 'required|string|max:20',
        'email' => 'nullable|email',
        'site_web' => 'nullable|url',
        'statut_juridique' => 'nullable|string|max:255',
        'numero_agrement' => 'nullable|string|max:100',
        'date_agrement' => 'nullable|date',
        'domaine_activite' => 'nullable|string|max:255',
        'budget_annuel' => 'nullable|numeric|min:0',
        'nombre_employes' => 'nullable|integer|min:0',
        'nombreBeneficiaire' => 'nullable|integer|min:0',
        'secteur_id' => 'required|exists:secteurs,id',
        'districts_id' => 'required|exists:districts,id',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSecteurFilter()
    {
        $this->resetPage();
    }

    public function updatingDistrictFilter()
    {
        $this->resetPage();
    }

    public function getAssociationsProperty()
    {
        return Association::with(['secteur', 'district', 'creator'])
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->when($this->secteurFilter, function ($query) {
                $query->where('secteur_id', $this->secteurFilter);
            })
            ->when($this->districtFilter, function ($query) {
                $query->where('districts_id', $this->districtFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);
    }

    public function createAssociation()
    {
        $this->resetAssociationForm();
        $this->showAssociationModal = true;
    }

    public function editAssociation($associationId)
    {
        $association = Association::find($associationId);
        $this->associationId = $association->id;
        $this->nom_asso_ar = $association->nom_asso_ar;
        $this->nom_de_l_asso = $association->nom_de_l_asso;
        $this->adresse = $association->adresse;
        $this->jeagraphie = $association->jeagraphie;
        $this->date_de_creation = $association->date_de_creation->format('Y-m-d');
        $this->tel = $association->tel;
        $this->remarque = $association->remarque;
        $this->nombreBeneficiaire = $association->nombreBeneficiaire;
        $this->email = $association->email;
        $this->site_web = $association->site_web;
        $this->statut_juridique = $association->statut_juridique;
        $this->numero_agrement = $association->numero_agrement;
        $this->date_agrement = $association->date_agrement?->format('Y-m-d');
        $this->domaine_activite = $association->domaine_activite;
        $this->budget_annuel = $association->budget_annuel;
        $this->nombre_employes = $association->nombre_employes;
        $this->secteur_id = $association->secteur_id;
        $this->districts_id = $association->districts_id;
        $this->is_active = $association->is_active;
        $this->showAssociationModal = true;
    }

    public function saveAssociation()
    {
        $this->validate();

        $associationData = [
            'nom_asso_ar' => $this->nom_asso_ar,
            'nom_de_l_asso' => $this->nom_de_l_asso,
            'adresse' => $this->adresse,
            'jeagraphie' => $this->jeagraphie,
            'date_de_creation' => $this->date_de_creation,
            'tel' => $this->tel,
            'remarque' => $this->remarque,
            'nombreBeneficiaire' => $this->nombreBeneficiaire,
            'email' => $this->email,
            'site_web' => $this->site_web,
            'statut_juridique' => $this->statut_juridique,
            'numero_agrement' => $this->numero_agrement,
            'date_agrement' => $this->date_agrement,
            'domaine_activite' => $this->domaine_activite,
            'budget_annuel' => $this->budget_annuel,
            'nombre_employes' => $this->nombre_employes,
            'secteur_id' => $this->secteur_id,
            'districts_id' => $this->districts_id,
            'is_active' => $this->is_active,
            'created_by' => Auth::id(),
        ];

        if ($this->associationId) {
            $association = Association::find($this->associationId);
            $association->update($associationData);
            session()->flash('message', 'Association updated successfully!');
        } else {
            Association::create($associationData);
            session()->flash('message', 'Association created successfully!');
        }

        $this->showAssociationModal = false;
        $this->resetAssociationForm();
    }

    public function confirmDelete($associationId)
    {
        $this->associationId = $associationId;
        $this->showDeleteModal = true;
    }

    public function deleteAssociation()
    {
        $association = Association::find($this->associationId);
        $association->delete();
        session()->flash('message', 'Association deleted successfully!');
        $this->showDeleteModal = false;
    }

    public function toggleAssociationStatus($associationId)
    {
        $association = Association::find($associationId);
        $association->update(['is_active' => !$association->is_active]);
        
        $action = $association->is_active ? 'activated' : 'deactivated';
        session()->flash('message', "Association {$action} successfully!");
    }

    public function resetAssociationForm()
    {
        $this->reset([
            'associationId', 'nom_asso_ar', 'nom_de_l_asso', 'adresse', 'jeagraphie',
            'date_de_creation', 'tel', 'remarque', 'nombreBeneficiaire', 'email',
            'site_web', 'statut_juridique', 'numero_agrement', 'date_agrement',
            'domaine_activite', 'budget_annuel', 'nombre_employes', 'secteur_id',
            'districts_id', 'is_active'
        ]);
    }

    public function render()
    {
        return view('livewire.association-manager', [
            'associations' => $this->associations,
            'secteurs' => Secteur::active()->get(),
            'districts' => District::active()->get(),
            'totalAssociations' => Association::count(),
            'activeAssociations' => Association::where('is_active', true)->count(),
            'totalBeneficiaires' => Association::sum('nombreBeneficiaire'),
            'totalEmployes' => Association::sum('nombre_employes'),
        ]);
    }
}