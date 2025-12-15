<?php
// app/Livewire/ReportCentreImpactFinance.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Centre;
use App\Models\ImpactBeneficiaire;
use App\Models\RessourceFinanciere;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    // Données
    public $centres = [];
    public $centresStats = [];
    public $globalStats = [];
    public $comparisonStats = [];
    public $performanceStats = [];
    public $impactStats = [];
    public $financeStats = [];

    public function mount()
    {
        $this->centres = Centre::orderBy('denomination')->get();
        $this->loadAllData();
    }

    public function loadAllData()
    {
        // Statistiques globales
        $totalCentres = Centre::count();
        $totalBeneficiaires = ImpactBeneficiaire::sum(DB::raw('nombre_inscrits_hommes + nombre_inscrits_femmes'));
        $totalRecettes = RessourceFinanciere::sum('total_recettes');
        $totalDepenses = RessourceFinanciere::sum('total_depenses');
        
        $this->globalStats = [
            'total_centres' => $totalCentres,
            'total_beneficiaires' => $totalBeneficiaires,
            'total_recettes' => $totalRecettes,
            'total_depenses' => $totalDepenses,
            'solde_global' => $totalRecettes - $totalDepenses,
            'taux_depenses' => $totalRecettes > 0 ? round(($totalDepenses / $totalRecettes) * 100, 1) : 0,
            'taux_feminisation' => $totalBeneficiaires > 0 ? 
                round((ImpactBeneficiaire::sum('nombre_inscrits_femmes') / $totalBeneficiaires) * 100, 1) : 0,
            'cout_moyen_beneficiaire' => $totalBeneficiaires > 0 ? 
                round(ImpactBeneficiaire::sum('charges_globales') / $totalBeneficiaires, 0) : 0,
        ];
        
        // Statistiques détaillées par centre
        $this->centresStats = $this->getCentresDetailedStats();
        
        // Statistiques d'impact agrégées
        $this->impactStats = $this->getImpactStats();
        
        // Statistiques financières agrégées
        $this->financeStats = $this->getFinanceStats();
        
        // Statistiques de comparaison
        $this->comparisonStats = $this->getComparisonStats();
        
        // Statistiques de performance
        $this->performanceStats = $this->getPerformanceStats();
    }
    
    private function getCentresDetailedStats()
    {
        $centres = Centre::withCount('impactBeneficiaires')
            ->withCount('ressourceFinancieres')
            ->get();
        
        return $centres->map(function($centre) {
            // Statistiques d'impact
            $impactStats = ImpactBeneficiaire::where('centre_id', $centre->id)
                ->select(
                    DB::raw('COUNT(*) as total_impacts'),
                    DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total_beneficiaires'),
                    DB::raw('SUM(nombre_inscrits_hommes) as hommes'),
                    DB::raw('SUM(nombre_inscrits_femmes) as femmes'),
                    DB::raw('SUM(charges_globales) as total_charges'),
                    DB::raw('AVG(heures_par_beneficiaire) as heures_moyennes')
                )->first();
            
            // Statistiques financières
            $financeStats = RessourceFinanciere::where('centre_id', $centre->id)
                ->select(
                    DB::raw('COUNT(*) as total_annees'),
                    DB::raw('SUM(total_recettes) as total_recettes'),
                    DB::raw('SUM(total_depenses) as total_depenses'),
                    DB::raw('AVG(total_recettes) as moyenne_recettes'),
                    DB::raw('AVG(total_depenses) as moyenne_depenses')
                )->first();
            
            // Calculs
            $totalBeneficiaires = $impactStats->total_beneficiaires ?? 0;
            $totalRecettes = $financeStats->total_recettes ?? 0;
            $totalDepenses = $financeStats->total_depenses ?? 0;
            $solde = $totalRecettes - $totalDepenses;
            
            return [
                'id' => $centre->id,
                'nom' => $centre->denomination,
                'code' => $centre->code ?? 'N/A',
                'ville' => $centre->ville ?? 'Non spécifié',
                
                // Impact
                'total_impacts' => $impactStats->total_impacts ?? 0,
                'total_beneficiaires' => $totalBeneficiaires,
                'hommes' => $impactStats->hommes ?? 0,
                'femmes' => $impactStats->femmes ?? 0,
                'taux_feminisation' => $totalBeneficiaires > 0 ? 
                    round(($impactStats->femmes / $totalBeneficiaires) * 100, 1) : 0,
                'total_charges' => $impactStats->total_charges ?? 0,
                'cout_moyen' => $totalBeneficiaires > 0 ? 
                    round($impactStats->total_charges / $totalBeneficiaires, 0) : 0,
                'heures_moyennes' => round($impactStats->heures_moyennes ?? 0, 1),
                
                // Finance
                'total_annees' => $financeStats->total_annees ?? 0,
                'total_recettes' => $totalRecettes,
                'total_depenses' => $totalDepenses,
                'solde' => $solde,
                'taux_depenses' => $totalRecettes > 0 ? 
                    round(($totalDepenses / $totalRecettes) * 100, 1) : 0,
                'moyenne_recettes' => round($financeStats->moyenne_recettes ?? 0, 0),
                'moyenne_depenses' => round($financeStats->moyenne_depenses ?? 0, 0),
                
                // Performance
                'score_impact' => $this->calculateImpactScore($impactStats),
                'score_finance' => $this->calculateFinanceScore($financeStats),
                'score_global' => 0, // Calculé plus bas
                
                // Métadonnées
                'has_impact' => ($impactStats->total_impacts ?? 0) > 0,
                'has_finance' => ($financeStats->total_annees ?? 0) > 0,
                'status' => $this->getCentreStatus($solde, $totalBeneficiaires)
            ];
        })->map(function($centre) {
            // Calcul du score global (moyenne pondérée)
            $centre['score_global'] = round(
                ($centre['score_impact'] * 0.6) + ($centre['score_finance'] * 0.4), 
                1
            );
            return $centre;
        })->sortByDesc('score_global')
          ->values()
          ->toArray();
    }
    
    private function calculateImpactScore($impactStats)
    {
        if (!$impactStats || $impactStats->total_impacts == 0) return 0;
        
        $score = 0;
        
        // Nombre de bénéficiaires (max 40 points)
        $score += min(40, ($impactStats->total_beneficiaires / 100) * 4);
        
        // Taux de féminisation (max 30 points)
        $tauxFemmes = $impactStats->total_beneficiaires > 0 ? 
            ($impactStats->femmes / $impactStats->total_beneficiaires) * 100 : 0;
        $score += min(30, $tauxFemmes * 0.3);
        
        // Heures moyennes (max 30 points)
        $score += min(30, ($impactStats->heures_moyennes ?? 0) * 0.3);
        
        return round($score, 1);
    }
    
    private function calculateFinanceScore($financeStats)
    {
        if (!$financeStats || $financeStats->total_annees == 0) return 0;
        
        $score = 0;
        
        // Solde positif (max 40 points)
        $solde = ($financeStats->total_recettes ?? 0) - ($financeStats->total_depenses ?? 0);
        if ($solde > 0) {
            $score += min(40, ($solde / max(1, $financeStats->total_recettes)) * 100);
        }
        
        // Contrôle des dépenses (max 40 points)
        $tauxDepenses = ($financeStats->total_recettes ?? 0) > 0 ? 
            (($financeStats->total_depenses ?? 0) / $financeStats->total_recettes) * 100 : 0;
        $score += min(40, max(0, 100 - $tauxDepenses) * 0.4);
        
        // Stabilité (nombre d'années) (max 20 points)
        $score += min(20, ($financeStats->total_annees ?? 0) * 4);
        
        return round($score, 1);
    }
    
    private function getCentreStatus($solde, $beneficiaires)
    {
        if ($beneficiaires == 0) return 'inactif';
        if ($solde < 0) return 'deficitaire';
        if ($solde > 0 && $beneficiaires > 100) return 'excellent';
        if ($solde >= 0 && $beneficiaires > 50) return 'bon';
        return 'moyen';
    }
    
    private function getImpactStats()
    {
        $stats = ImpactBeneficiaire::select(
            DB::raw('COUNT(DISTINCT centre_id) as centres_actifs'),
            DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total_beneficiaires'),
            DB::raw('AVG(nombre_inscrits_hommes + nombre_inscrits_femmes) as moyenne_par_centre'),
            DB::raw('SUM(charges_globales) as total_charges'),
            DB::raw('AVG(charges_globales) as charges_moyennes'),
            DB::raw('AVG(heures_par_beneficiaire) as heures_moyennes')
        )->first();
        
        return [
            'centres_actifs' => $stats->centres_actifs ?? 0,
            'total_beneficiaires' => $stats->total_beneficiaires ?? 0,
            'moyenne_par_centre' => round($stats->moyenne_par_centre ?? 0, 1),
            'total_charges' => $stats->total_charges ?? 0,
            'charges_moyennes' => round($stats->charges_moyennes ?? 0, 0),
            'heures_moyennes' => round($stats->heures_moyennes ?? 0, 1)
        ];
    }
    
    private function getFinanceStats()
    {
        $stats = RessourceFinanciere::select(
            DB::raw('COUNT(DISTINCT centre_id) as centres_finances'),
            DB::raw('AVG(total_recettes) as moyenne_recettes'),
            DB::raw('AVG(total_depenses) as moyenne_depenses'),
            DB::raw('SUM(total_recettes - total_depenses) as solde_total'),
            DB::raw('AVG(total_recettes - total_depenses) as solde_moyen'),
            DB::raw('AVG((total_depenses / NULLIF(total_recettes, 0)) * 100) as taux_depenses_moyen')
        )->first();
        
        return [
            'centres_finances' => $stats->centres_finances ?? 0,
            'moyenne_recettes' => round($stats->moyenne_recettes ?? 0, 0),
            'moyenne_depenses' => round($stats->moyenne_depenses ?? 0, 0),
            'solde_total' => $stats->solde_total ?? 0,
            'solde_moyen' => round($stats->solde_moyen ?? 0, 0),
            'taux_depenses_moyen' => round($stats->taux_depenses_moyen ?? 0, 1)
        ];
    }
    
    private function getComparisonStats()
    {
        $centres = collect($this->centresStats);
        
        return [
            'labels' => $centres->pluck('nom')->take(8)->toArray(),
            'datasets' => [
                [
                    'label' => 'Bénéficiaires',
                    'data' => $centres->pluck('total_beneficiaires')->take(8)->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)'
                ],
                [
                    'label' => 'Recettes (k DH)',
                    'data' => $centres->pluck('total_recettes')->take(8)->map(function($v) {
                        return $v / 1000;
                    })->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.7)'
                ]
            ]
        ];
    }
    
    private function getPerformanceStats()
    {
        $centres = collect($this->centresStats);
        
        return [
            'labels' => ['Score Impact', 'Score Finance', 'Bénéficiaires', 'Solde Financier', 'Efficacité'],
            'datasets' => $centres->take(3)->map(function($centre, $index) {
                $colors = ['#FF6384', '#36A2EB', '#FFCE56'];
                return [
                    'label' => $centre['nom'],
                    'data' => [
                        $centre['score_impact'],
                        $centre['score_finance'],
                        min(100, $centre['total_beneficiaires'] / 10),
                        min(100, max(0, $centre['solde'] / 10000)),
                        $centre['taux_depenses'] > 0 ? min(100, 100 - $centre['taux_depenses']) : 0
                    ],
                    'backgroundColor' => 'rgba(' . $this->hexToRgb($colors[$index]) . ', 0.2)',
                    'borderColor' => $colors[$index],
                    'pointBackgroundColor' => $colors[$index]
                ];
            })->toArray()
        ];
    }
    
    private function hexToRgb($hex)
    {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return "$r, $g, $b";
    }

    public function render()
    {
        return view('livewire.reports');
    }
}