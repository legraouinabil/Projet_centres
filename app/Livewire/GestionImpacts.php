<?php
// app/Livewire/GestionImpacts.php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Centre;
use App\Models\ImpactBeneficiaire;
use App\Models\ImpactFormationPro;
use App\Models\ImpactAnimation;
use App\Models\ImpactHandicap;
use App\Models\ImpactEps;
use Illuminate\Support\Facades\DB;
use App\Models\Partenaire;

class GestionImpacts extends Component
{
    use WithPagination;

    // Filtres
    public $centres = [];
    public $centre_id;
    public $annee;
    public $domaine = '';
    public $search = '';
    
    // Données du formulaire
    public $impact_id;
    public $intitule_filiere_discipline;
    public $nombre_inscrits_hommes = 0;
    public $nombre_inscrits_femmes = 0;
    public $heures_par_beneficiaire = 0;
    public $nombre_abandons = 0;
    public $masse_salariale = 0;
    public $charges_globales = 0;
    
    // Données spécifiques par domaine
    public $donnees_specifiques = [];
    
    // États des modals
    public $showForm = false;
    public $showDetailsModal = false;
    public $showPartenairesModal = false;
    public $isEditing = false;
    
    // Champs partenaire (modal)
    public $partenaire_nom;
    public $partenaire_evenements = 0;
    public $partenaire_participations = 0;
    public $partenaire_trophies = 0;
    
    // Impact sélectionné
    public $selectedImpact = null;
    // Message de succès affiché immédiatement pour Livewire
    public $successMessage = null;
    // Livewire listeners
    protected $listeners = [
        'clearSuccess' => 'clearSuccess',
    ];

    protected $rules = [
        'centre_id' => 'required|exists:centres,id',
        'annee' => 'required|digits:4',
        'domaine' => 'required|in:formation_pro,animation_culturelle_sportive,handicap,eps',
        'intitule_filiere_discipline' => 'required|string|max:255',
        'nombre_inscrits_hommes' => 'required|integer|min:0',
        'nombre_inscrits_femmes' => 'required|integer|min:0',
        'heures_par_beneficiaire' => 'required|integer|min:0',
        'nombre_abandons' => 'required|integer|min:0',
        'masse_salariale' => 'required|numeric|min:0',
        'charges_globales' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->centres = Centre::get();
       
        $this->initializeDonneesSpecifiques();
    }

    public function initializeDonneesSpecifiques()
    {
        $this->donnees_specifiques = match($this->domaine) {
            'formation_pro' => [
                'nombre_filieres' => 1,
                'nombre_laureats' => 0,
                'taux_insertion_professionnelle' => 0,
                'moyenne_premier_salaire' => 0,
                'nombre_inscrits_1ere_annee' => 0,
                'nombre_inscrits_2eme_annee' => 0,
            ],
            'animation_culturelle_sportive' => [
                'nombre_disciplines' => 1,
                'nombre_inscrits_ecoles' => 0,
                'nombre_inscrits_particuliers' => 0,
                'nombre_conventions' => 0,
                'nombre_evenements_organises' => 0,
                'nombre_participations_competitions' => 0,
                'nombre_trophees_gagnes' => 0,
            ],
            'handicap' => [
                'nombre_handicaps_traites' => 1,
                'heures_medecin_an' => 0,
                'heures_assistant_social_an' => 0,
                'heures_orthophoniste_an' => 0,
                'heures_kinesitherapie_an' => 0,
                'heures_psychologue_an' => 0,
            ],
            'eps' => [
                'nombre_handicaps_traites' => 1,
                'heures_medecin_an' => 0,
                'heures_assistant_social_an' => 0,
                'heures_psychologue_an' => 0,
            ],
            default => []
        };
    }

    // Méthode pour afficher les détails
    public function showDetails($impactId)
    {
        $this->selectedImpact = ImpactBeneficiaire::with([
            'centre',
            'formationPro', 
            'animation',
            'handicap',
            'eps',
            'partenaires'
        ])->findOrFail($impactId);
        
        $this->showDetailsModal = true;
    }

    // Fermer le modal de détails
    public function closeDetails()
    {
        $this->showDetailsModal = false;
        $this->selectedImpact = null;
    }

    // Afficher la gestion des partenaires
    public function showGestionPartenaires($impactId)
    {
        $this->selectedImpact = ImpactBeneficiaire::with('partenaires')->findOrFail($impactId);
        $this->showPartenairesModal = true;
    }

    // Fermer le modal de partenaires
    public function closePartenaires()
    {
        $this->showPartenairesModal = false;
        $this->selectedImpact = null;
    }

    // Vérifier s'il y a des filtres actifs
    public function getHasActiveFiltersProperty()
    {
        return !empty($this->search) || 
               !empty($this->centre_id) || 
               !empty($this->annee) || 
               !empty($this->domaine);
    }

    // Réinitialiser les filtres
    public function resetFilters()
    {
        $this->reset(['search', 'centre_id', 'annee', 'domaine']);
        $this->resetPage();
    }

    public function updatedDomaine($value)
    {
        $this->initializeDonneesSpecifiques();
        $this->resetValidation();
    }

    public function createImpact()
    {
        $this->validate();

        DB::transaction(function () {
            // Créer l'impact principal
            $impact = ImpactBeneficiaire::create([
                'centre_id' => $this->centre_id,
                'domaine' => $this->domaine,
                'annee' => $this->annee,
                'intitule_filiere_discipline' => $this->intitule_filiere_discipline,
                'nombre_inscrits_hommes' => $this->nombre_inscrits_hommes,
                'nombre_inscrits_femmes' => $this->nombre_inscrits_femmes,
                'heures_par_beneficiaire' => $this->heures_par_beneficiaire,
                'nombre_abandons' => $this->nombre_abandons,
                'masse_salariale' => $this->masse_salariale,
                'charges_globales' => $this->charges_globales,
            ]);

            // Créer les données spécifiques
            $this->createDonneesSpecifiques($impact->id);

            $this->resetForm();
            session()->flash('success', 'Impact bénéficiaire créé avec succès.');
            $this->successMessage = 'Impact bénéficiaire créé avec succès.';
        });
    }

    public function updateImpact()
    {
        $this->validate();

        DB::transaction(function () {
            $impact = ImpactBeneficiaire::findOrFail($this->impact_id);
            
            $impact->update([
                'centre_id' => $this->centre_id,
                'domaine' => $this->domaine,
                'annee' => $this->annee,
                'intitule_filiere_discipline' => $this->intitule_filiere_discipline,
                'nombre_inscrits_hommes' => $this->nombre_inscrits_hommes,
                'nombre_inscrits_femmes' => $this->nombre_inscrits_femmes,
                'heures_par_beneficiaire' => $this->heures_par_beneficiaire,
                'nombre_abandons' => $this->nombre_abandons,
                'masse_salariale' => $this->masse_salariale,
                'charges_globales' => $this->charges_globales,
            ]);

            // Mettre à jour les données spécifiques
            $this->updateDonneesSpecifiques($impact->id);

            $this->resetForm();
            session()->flash('success', 'Impact bénéficiaire modifié avec succès.');
            $this->successMessage = 'Impact bénéficiaire modifié avec succès.';
        });
    }

    protected function createDonneesSpecifiques($impactId)
    {
        switch ($this->domaine) {
            case 'formation_pro':
                ImpactFormationPro::create(array_merge(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                ));
                break;
                
            case 'animation_culturelle_sportive':
                ImpactAnimation::create(array_merge(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                ));
                break;
                
            case 'handicap':
                ImpactHandicap::create(array_merge(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                ));
                break;
                
            case 'eps':
                ImpactEps::create(array_merge(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                ));
                break;
        }
    }

    protected function updateDonneesSpecifiques($impactId)
    {
        switch ($this->domaine) {
            case 'formation_pro':
                ImpactFormationPro::updateOrCreate(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                );
                break;
                
            case 'animation_culturelle_sportive':
                ImpactAnimation::updateOrCreate(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                );
                break;
                
            case 'handicap':
                ImpactHandicap::updateOrCreate(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                );
                break;
                
            case 'eps':
                ImpactEps::updateOrCreate(
                    ['impact_beneficiaire_id' => $impactId],
                    $this->donnees_specifiques
                );
                break;
        }
    }

    public function editImpact($impactId)
    {
        $impact = ImpactBeneficiaire::with(['formationPro', 'animation', 'handicap', 'eps'])->findOrFail($impactId);
        
        $this->impact_id = $impact->id;
        $this->centre_id = $impact->centre_id;
        $this->domaine = $impact->domaine;
        $this->annee = $impact->annee;
        $this->intitule_filiere_discipline = $impact->intitule_filiere_discipline;
        $this->nombre_inscrits_hommes = $impact->nombre_inscrits_hommes;
        $this->nombre_inscrits_femmes = $impact->nombre_inscrits_femmes;
        $this->heures_par_beneficiaire = $impact->heures_par_beneficiaire;
        $this->nombre_abandons = $impact->nombre_abandons;
        $this->masse_salariale = $impact->masse_salariale;
        $this->charges_globales = $impact->charges_globales;

        // Charger les données spécifiques
        $this->loadDonneesSpecifiques($impact);

        $this->isEditing = true;
        $this->showForm = true;
    }

    protected function loadDonneesSpecifiques($impact)
    {
        $this->initializeDonneesSpecifiques();

        switch ($this->domaine) {
            case 'formation_pro':
                if ($impact->formationPro) {
                    $this->donnees_specifiques = $impact->formationPro->toArray();
                }
                break;
                
            case 'animation_culturelle_sportive':
                if ($impact->animation) {
                    $this->donnees_specifiques = $impact->animation->toArray();
                }
                break;
                
            case 'handicap':
                if ($impact->handicap) {
                    $this->donnees_specifiques = $impact->handicap->toArray();
                }
                break;
                
            case 'eps':
                if ($impact->eps) {
                    $this->donnees_specifiques = $impact->eps->toArray();
                }
                break;
        }
    }

    public function deleteImpact($impactId)
    {
        $impact = ImpactBeneficiaire::findOrFail($impactId);
        $impact->delete();
        
        session()->flash('success', 'Impact bénéficiaire supprimé avec succès.');
        $this->successMessage = 'Impact bénéficiaire supprimé avec succès.';
    }

    public function resetForm()
    {
        $this->reset([
            'impact_id', 'centre_id', 'intitule_filiere_discipline',
            'nombre_inscrits_hommes', 'nombre_inscrits_femmes',
            'heures_par_beneficiaire', 'nombre_abandons',
            'masse_salariale', 'charges_globales',
            'isEditing', 'showForm', 'showDetailsModal', 'showPartenairesModal'
        ]);
        $this->initializeDonneesSpecifiques();
        $this->selectedImpact = null;
        $this->resetPartenaireForm();
    }

    private function resetPartenaireForm()
    {
        $this->reset(['partenaire_nom', 'partenaire_evenements', 'partenaire_participations', 'partenaire_trophies']);
        $this->resetErrorBag();
    }

    public function savePartenaire()
    {
        $this->validate([
            'partenaire_nom' => 'required|string|max:255',
            'partenaire_evenements' => 'nullable|integer|min:0',
            'partenaire_participations' => 'nullable|integer|min:0',
            'partenaire_trophies' => 'nullable|integer|min:0',
        ]);

        if (! $this->selectedImpact) {
            $this->addError('selectedImpact', 'Aucun impact sélectionné.');
            return;
        }

        // Create partenaire with the fields available in the table (nom + impact_beneficiaire_id).
        Partenaire::create([
            'impact_beneficiaire_id' => $this->selectedImpact->id,
            'nom' => $this->partenaire_nom,
        ]);

        // Refresh selected impact to include new partenaire
        $this->selectedImpact = $this->selectedImpact->fresh('partenaires');

        $this->resetPartenaireForm();
        $this->showPartenairesModal = false;

        session()->flash('success', 'Partenaire ajouté avec succès.');
        $this->successMessage = 'Partenaire ajouté avec succès.';
    }

    public function deletePartenaire($id)
    {
        $partenaire = Partenaire::findOrFail($id);
        $partenaire->delete();

        // Refresh selected impact partenaires
        if ($this->selectedImpact) {
            $this->selectedImpact = $this->selectedImpact->fresh('partenaires');
        }

        session()->flash('success', 'Partenaire supprimé avec succès.');
        $this->successMessage = 'Partenaire supprimé avec succès.';
    }

    // Clear transient success message (called from client after timeout)
    public function clearSuccess()
    {
        $this->successMessage = null;
    }

    public function getImpactsQueryProperty()
    {
        $query = ImpactBeneficiaire::with(['centre', 'formationPro', 'animation', 'handicap', 'eps', 'partenaires']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('intitule_filiere_discipline', 'like', '%'.$this->search.'%')
                  ->orWhereHas('centre', function($q) {
                      $q->where('denomination', 'like', '%'.$this->search.'%');
                  });
            });
        }

        if ($this->centre_id) {
            $query->where('centre_id', $this->centre_id);
        }

        if ($this->annee) {
            $query->where('annee', $this->annee);
        }

        if ($this->domaine) {
            $query->where('domaine', $this->domaine);
        }

        return $query->orderBy('annee', 'desc')->orderBy('created_at', 'desc');
    }

    public function getImpactsProperty()
    {
        return $this->impactsQuery->paginate(10);
    }

    public function getStatsProperty()
    {
        $query = $this->impactsQuery;
        
        $totalBeneficiaires = $query->sum(DB::raw('nombre_inscrits_hommes + nombre_inscrits_femmes'));
        $totalAbandons = $query->sum('nombre_abandons');
        $totalCharges = $query->sum('charges_globales');
        
        $tauxAbandonMoyen = $totalBeneficiaires > 0 ? ($totalAbandons / $totalBeneficiaires) * 100 : 0;
        $coutMoyenBeneficiaire = $totalBeneficiaires > 0 ? $totalCharges / $totalBeneficiaires : 0;
        
        return [
            'total_impacts' => $query->count(),
            'total_beneficiaires' => $totalBeneficiaires,
            'total_centres' => $query->distinct('centre_id')->count('centre_id'),
            'taux_abandon_moyen' => $tauxAbandonMoyen,
            'cout_moyen_beneficiaire' => $coutMoyenBeneficiaire,
        ];
    }

    public function render()
    {
        return view('livewire.gestion-impacts', [
            'impacts' => $this->impacts,
            'stats' => $this->stats,
        ]);
    }
}