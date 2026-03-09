<?php
// app/Http/Livewire/AssociationManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Association;
use App\Models\Secteur;
use App\Models\District;
use App\Models\Centre;
use App\Models\Programme;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class AssociationManager extends Component
{
    use WithPagination;

    public $search = '';
    public $secteurFilter = '';
    public $districtFilter = '';
    public $programmeFilter = '';
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
    public $president_name = '';
    public $president_email = '';
    public $president_cin = '';
    public $site_web = '';
    public $statut_juridique = '';
    public $numero_agrement = '';
    public $date_agrement = '';
    public $domaine_activite = '';
    public $budget_annuel = 0;
    public $nombre_employes = 0;
    public $centre_ids = [];
    public $programme_ids = [];
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
        'president_name' => 'nullable|string|max:255',
        'president_email' => 'nullable|email',
        'president_cin' => 'nullable|string|max:100',
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
        'centre_ids' => 'array',
        'centre_ids.*' => 'exists:centres,id',
        'programme_ids' => 'array',
        'programme_ids.*' => 'exists:programmes,id',
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

    public function updatingProgrammeFilter()
    {
        $this->resetPage();
    }

    public function getAssociationsProperty()
    {
        return Association::with(['secteur', 'district', 'creator', 'latestDossier', 'programmes'])
            ->when($this->search, function ($query) {
                $query->search($this->search);
            })
            ->when($this->secteurFilter, function ($query) {
                $query->where('secteur_id', $this->secteurFilter);
            })
            ->when($this->districtFilter, function ($query) {
                $query->where('districts_id', $this->districtFilter);
            })
            ->when($this->programmeFilter, function ($query) {
                $query->whereHas('programmes', function ($q) {
                    $q->where('programmes.id', $this->programmeFilter);
                });
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
        $this->president_name = $association->president_name;
        $this->president_email = $association->president_email;
        $this->president_cin = $association->president_cin;
        $this->secteur_id = $association->secteur_id;
        $this->districts_id = $association->districts_id;
        $this->is_active = $association->is_active;
        $this->centre_ids = $association->centres()->pluck('centres.id')->toArray();
        $this->programme_ids = $association->programmes()->pluck('programmes.id')->toArray();
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
            'president_name' => $this->president_name,
            'president_email' => $this->president_email,
            'president_cin' => $this->president_cin,
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
            $association->centres()->sync($this->centre_ids ?? []);
            $association->programmes()->sync($this->programme_ids ?? []);
            // Log update
            ActivityLogger::log('association.updated', $association, ['nom_de_l_asso' => $association->nom_de_l_asso]);
            session()->flash('message', 'Association updated successfully!');
        } else {
            $association = Association::create($associationData);
            $association->centres()->sync($this->centre_ids ?? []);
            $association->programmes()->sync($this->programme_ids ?? []);
            // Log creation
            ActivityLogger::log('association.created', $association, ['nom_de_l_asso' => $association->nom_de_l_asso]);
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
        // capture some data before deletion
        $data = ['nom_de_l_asso' => $association->nom_de_l_asso, 'id' => $association->id];
        $association->delete();
        ActivityLogger::log('association.deleted', null, $data);
        session()->flash('message', 'Association deleted successfully!');
        $this->showDeleteModal = false;
    }

    public function toggleAssociationStatus($associationId)
    {
        $association = Association::find($associationId);
        $association->update(['is_active' => !$association->is_active]);
        
        $action = $association->is_active ? 'association.activated' : 'association.deactivated';
        ActivityLogger::log($action, $association, ['is_active' => $association->is_active]);
        $label = $association->is_active ? 'activée' : 'désactivée';
        session()->flash('message', "Association {$label} successfully!");
    }

    public function resetAssociationForm()
    {
        $this->reset([
            'associationId', 'nom_asso_ar', 'nom_de_l_asso', 'adresse', 'jeagraphie',
            'date_de_creation', 'tel', 'remarque', 'nombreBeneficiaire', 'email',
            'president_name', 'president_email', 'president_cin', 'site_web', 'statut_juridique', 'numero_agrement', 'date_agrement',
            'domaine_activite', 'budget_annuel', 'nombre_employes', 'secteur_id',
            'districts_id', 'is_active', 'centre_ids', 'programme_ids'
        ]);
    }

    public function render()
    {
        return view('livewire.association-manager', [
            'associations' => $this->associations,
            'secteurs' => Secteur::active()->get(),
            'districts' => District::active()->get(),
            'centres' => Centre::orderBy('denomination')->get(),
            'programmes' => Programme::orderBy('name')->get(),
            'totalAssociations' => Association::count(),
            'activeAssociations' => Association::where('is_active', true)->count(),
            'totalBeneficiaires' => Association::sum('nombreBeneficiaire'),
            'totalEmployes' => Association::sum('nombre_employes'),
        ]);
    }
}