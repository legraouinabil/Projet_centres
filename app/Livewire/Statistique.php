<?php
// app/Livewire/StatistiqueGestionImpact.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\ImpactBeneficiaire;
use Illuminate\Support\Facades\DB;

class Statistique extends Component
{
    // Données
    public $stats = [];
    public $domainesStats = [];
    public $centresStats = [];
    public $evolutionStats = [];
    public $performanceStats = [];
    public $comparisonStats = []; // Nouveau pour le graphique de comparaison

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Calculer les statistiques globales
        $totalBeneficiaires = ImpactBeneficiaire::sum(DB::raw('nombre_inscrits_hommes + nombre_inscrits_femmes'));
        $totalHommes = ImpactBeneficiaire::sum('nombre_inscrits_hommes');
        $totalFemmes = ImpactBeneficiaire::sum('nombre_inscrits_femmes');
        $totalCharges = ImpactBeneficiaire::sum('charges_globales');
        $totalAbandons = ImpactBeneficiaire::sum('nombre_abandons');
        $totalHeures = ImpactBeneficiaire::sum(DB::raw('(nombre_inscrits_hommes + nombre_inscrits_femmes) * heures_par_beneficiaire'));
        
        $this->stats = [
            'total_impacts' => ImpactBeneficiaire::count(),
            'total_beneficiaires' => $totalBeneficiaires,
            'total_hommes' => $totalHommes,
            'total_femmes' => $totalFemmes,
            'total_abandons' => $totalAbandons,
            'total_heures' => $totalHeures,
            'total_charges' => $totalCharges,
            'taux_feminisation' => $totalBeneficiaires > 0 ? round(($totalFemmes / $totalBeneficiaires) * 100, 1) : 0,
            'taux_abandon' => $totalBeneficiaires > 0 ? round(($totalAbandons / $totalBeneficiaires) * 100, 1) : 0,
            'cout_moyen' => $totalBeneficiaires > 0 ? round($totalCharges / $totalBeneficiaires) : 0,
            'heures_moyennes' => $totalBeneficiaires > 0 ? round($totalHeures / $totalBeneficiaires, 1) : 0,
        ];
        
        // Domaines
        $this->domainesStats = ImpactBeneficiaire::select('domaine', 
                DB::raw('COUNT(*) as count'), 
                DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total'),
                DB::raw('AVG(heures_par_beneficiaire) as heures_moyennes')
            )
            ->groupBy('domaine')
            ->get()
            ->map(function($item) {
                return [
                    'domaine' => $item->domaine,
                    'label' => $this->getDomaineLabel($item->domaine),
                    'count' => $item->count,
                    'total' => $item->total,
                    'heures_moyennes' => round($item->heures_moyennes, 1),
                    'color' => $this->getColorForDomain($item->domaine)
                ];
            })
            ->toArray();
        
        // Centres (top 8)
        $this->centresStats = ImpactBeneficiaire::with('centre')
            ->select('centre_id', 
                DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total'),
                DB::raw('AVG(charges_globales / (nombre_inscrits_hommes + nombre_inscrits_femmes)) as cout_moyen')
            )
            ->groupBy('centre_id')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->centre ? $item->centre->denomination : 'Inconnu',
                    'total' => $item->total,
                    'cout_moyen' => round($item->cout_moyen ?? 0, 0)
                ];
            })
            ->toArray();
        
        // Évolution sur 5 ans
        $currentYear = date('Y');
        $startYear = $currentYear - 4;
        
        $this->evolutionStats = ImpactBeneficiaire::select(
                'annee',
                DB::raw('SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total_beneficiaires'),
                DB::raw('SUM(charges_globales) as total_charges'),
                DB::raw('AVG(heures_par_beneficiaire) as heures_moyennes')
            )
            ->whereBetween('annee', [$startYear, $currentYear])
            ->groupBy('annee')
            ->orderBy('annee')
            ->get()
            ->map(function($item) {
                return [
                    'annee' => $item->annee,
                    'total_beneficiaires' => $item->total_beneficiaires,
                    'total_charges' => $item->total_charges,
                    'heures_moyennes' => round($item->heures_moyennes, 1)
                ];
            })
            ->toArray();
        
        // Statistiques de performance (Radar Chart)
        $this->loadPerformanceStats();
        
        // Statistiques de comparaison par domaine (Nouveau - Bar Chart groupé)
        $this->loadComparisonStats();
    }
    
    private function loadPerformanceStats()
    {
        // Calcul des indicateurs de performance
        $this->performanceStats = [
            'labels' => ['Féminisation', 'Rétention', 'Efficacité Coût', 'Productivité', 'Couverture'],
            'datasets' => [
                [
                    'label' => 'Performance',
                    'data' => [
                        // Taux de féminisation
                        $this->stats['taux_feminisation'],
                        
                        // Taux de rétention (100% - taux d'abandon)
                        100 - $this->stats['taux_abandon'],
                        
                        // Efficacité coût (inversé: plus c'est bas, mieux c'est)
                        max(0, 100 - ($this->stats['cout_moyen'] / 100)),
                        
                        // Productivité (heures/beneficiaire)
                        min(100, $this->stats['heures_moyennes']),
                        
                        // Couverture (nombre d'années avec données)
                        min(100, ImpactBeneficiaire::distinct('annee')->count('annee') * 20)
                    ],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(54, 162, 235, 1)'
                ],
                [
                    'label' => 'Cible',
                    'data' => [45, 90, 50, 40, 60],
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 1)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(255, 99, 132, 1)'
                ]
            ]
        ];
    }
    
    private function loadComparisonStats()
    {
        // Comparaison par domaine pour un graphique groupé
        $domaines = ['formation_pro', 'animation_culturelle_sportive', 'handicap', 'eps'];
        
        $this->comparisonStats = [
            'labels' => array_map([$this, 'getDomaineLabel'], $domaines),
            'datasets' => [
                [
                    'label' => 'Bénéficiaires',
                    'data' => array_map(function($domaine) {
                        $stat = collect($this->domainesStats)->firstWhere('domaine', $domaine);
                        return $stat['total'] ?? 0;
                    }, $domaines),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.7)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Heures moyennes',
                    'data' => array_map(function($domaine) {
                        $stat = collect($this->domainesStats)->firstWhere('domaine', $domaine);
                        return $stat['heures_moyennes'] ?? 0;
                    }, $domaines),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.7)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Nombre d\'impacts',
                    'data' => array_map(function($domaine) {
                        $stat = collect($this->domainesStats)->firstWhere('domaine', $domaine);
                        return $stat['count'] ?? 0;
                    }, $domaines),
                    'backgroundColor' => 'rgba(255, 206, 86, 0.7)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 1
                ]
                
            ]
        ];
    }

    private function getDomaineLabel($domaine)
    {
        $labels = [
            'formation_pro' => 'Formation Pro',
            'animation_culturelle_sportive' => 'Animation',
            'handicap' => 'Handicap',
            'eps' => 'EPS',
        ];
        
        return $labels[$domaine] ?? $domaine;
    }

    private function getColorForDomain($domaine)
    {
        $colors = [
            'formation_pro' => '#FF6384',        // Rouge
            'animation_culturelle_sportive' => '#36A2EB', // Bleu
            'handicap' => '#FFCE56',             // Jaune
            'eps' => '#4BC0C0',                  // Turquoise
        ];
        
        return $colors[$domaine] ?? '#9966FF';
    }

    public function render()
    {
        return view('livewire.statistique');
    }
}