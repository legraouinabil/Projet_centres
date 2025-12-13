<?php
// app/Livewire/StatistiqueGestionImpact.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Centre;
use App\Models\ImpactBeneficiaire;
use Illuminate\Support\Facades\DB;

class Statistique extends Component
{
    // Filtres
    public $centres = [];
    public $centre_id;
    public $annee;
    public $domaine = '';
    
    // Données
    public $stats = [];
    public $domainesStats = [];
    public $centresStats = [];
    public $evolutionStats = [];

    public function mount()
    {
        $this->centres = Centre::orderBy('denomination')->get();
        $this->annee = date('Y');
        $this->loadData();
    }

    public function loadData()
    {
        // Requête de base
        $query = ImpactBeneficiaire::query();
        
        // Appliquer les filtres
        if ($this->centre_id) {
            $query->where('centre_id', $this->centre_id);
        }
        
        if ($this->annee) {
            $query->where('annee', $this->annee);
        }
        
        if ($this->domaine) {
            $query->where('domaine', $this->domaine);
        }
        
        // Calculer les statistiques
        $totalBeneficiaires = $query->sum(DB::raw('nombre_inscrits_hommes + nombre_inscrits_femmes'));
        $totalHommes = $query->sum('nombre_inscrits_hommes');
        $totalFemmes = $query->sum('nombre_inscrits_femmes');
        $totalCharges = $query->sum('charges_globales');
        
        $this->stats = [
            'total_impacts' => $query->count(),
            'total_beneficiaires' => $totalBeneficiaires,
            'total_hommes' => $totalHommes,
            'total_femmes' => $totalFemmes,
            'taux_feminisation' => $totalBeneficiaires > 0 ? round(($totalFemmes / $totalBeneficiaires) * 100, 1) : 0,
            'cout_moyen' => $totalBeneficiaires > 0 ? round($totalCharges / $totalBeneficiaires) : 0,
            'total_charges' => $totalCharges,
        ];
        
        // Domaines
        $this->domainesStats = $query
            ->select('domaine', DB::raw('COUNT(*) as count'), DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total'))
            ->groupBy('domaine')
            ->get()
            ->map(function($item) {
                return [
                    'domaine' => $item->domaine,
                    'label' => $this->getDomaineLabel($item->domaine),
                    'count' => $item->count,
                    'total' => $item->total,
                    'color' => $this->getColorForDomain($item->domaine)
                ];
            })
            ->toArray();
        
        // Centres (top 5 pour éviter la surcharge)
        $this->centresStats = ImpactBeneficiaire::with('centre')
            ->select('centre_id', DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total'))
            ->when($this->annee, function($q) {
                $q->where('annee', $this->annee);
            })
            ->when($this->domaine, function($q) {
                $q->where('domaine', $this->domaine);
            })
            ->when($this->centre_id, function($q) {
                $q->where('centre_id', $this->centre_id);
            })
            ->groupBy('centre_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->centre ? substr($item->centre->denomination, 0, 15) . '...' : 'Inconnu',
                    'total' => $item->total
                ];
            })
            ->toArray();
        
        // Évolution (3 ans)
        $currentYear = $this->annee ?: date('Y');
        $startYear = $currentYear - 2;
        
        $this->evolutionStats = ImpactBeneficiaire::select(
                'annee',
                DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total_beneficiaires')
            )
            ->when($this->centre_id, function($q) {
                $q->where('centre_id', $this->centre_id);
            })
            ->when($this->domaine, function($q) {
                $q->where('domaine', $this->domaine);
            })
            ->whereBetween('annee', [$startYear, $currentYear])
            ->groupBy('annee')
            ->orderBy('annee')
            ->get()
            ->map(function($item) {
                return [
                    'annee' => $item->annee,
                    'total_beneficiaires' => $item->total_beneficiaires
                ];
            })
            ->toArray();
    }

    private function getDomaineLabel($domaine)
    {
        $labels = [
            'formation_pro' => 'Formation',
            'animation_culturelle_sportive' => 'Animation',
            'handicap' => 'Handicap',
            'eps' => 'EPS',
        ];
        
        return $labels[$domaine] ?? $domaine;
    }

    private function getColorForDomain($domaine)
    {
        $colors = [
            'formation_pro' => '#FF6384',
            'animation_culturelle_sportive' => '#36A2EB',
            'handicap' => '#FFCE56',
            'eps' => '#4BC0C0',
        ];
        
        return $colors[$domaine] ?? '#9966FF';
    }

    public function updated()
    {
        $this->loadData();
        self::dispatch('updated');
    }

    public function resetFilters()
    {
        $this->reset(['centre_id', 'annee', 'domaine']);
        $this->annee = date('Y');
        $this->loadData();
        self::dispatch('updated');
    }

    public function render()
    {
        return view('livewire.statistique');
    }
}