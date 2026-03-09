<?php
// app/Livewire/ReportRessourcesFinancieres.php
namespace App\Livewire;

use App\Models\RessourceFinanciere;
use App\Models\Centre;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Reports extends Component
{
    // Data properties
    public array $stats = [];
    public array $centresStats = [];
    public array $evolutionStats = [];
    
    // 4 Different Charts
    public array $barChart = [];          // Bar Chart: Annual comparison
    public array $doughnutChart = [];     // Doughnut Chart: Budget distribution by category
    public array $pieChart = [];          // Pie Chart: Center performance distribution
    public array $lineChart = [];         // Line Chart: Trend analysis
    
    // Centers list
    public Collection $centres;
    
    // Chart configuration
    private const CHART_COLORS = [
        'primary' => ['#3b82f6', '#1d4ed8', '#60a5fa', '#93c5fd'], // Blue shades
        'success' => ['#10b981', '#059669', '#34d399', '#6ee7b7'], // Green shades
        'warning' => ['#f59e0b', '#d97706', '#fbbf24', '#fcd34d'], // Amber shades
        'danger'  => ['#ef4444', '#dc2626', '#f87171', '#fca5a5'], // Red shades
        'purple'  => ['#8b5cf6', '#7c3aed', '#a78bfa', '#c4b5fd'], // Purple shades
        'pink'    => ['#ec4899', '#db2777', '#f472b6', '#f9a8d4'], // Pink shades
    ];

    public function mount(): void
    {
        $this->centres = Centre::orderBy('denomination')->get();
        $this->loadAllData();
    }

    public function loadAllData(): void
    {
        $this->loadGlobalStats();
        $this->loadCentresStats();
        $this->loadEvolutionStats();
        $this->loadBarChart();
        $this->loadDoughnutChart();
        $this->loadPieChart();
        $this->loadLineChart();
    }
    
    private function loadGlobalStats(): void
    {
        $aggregates = RessourceFinanciere::select([
                DB::raw('SUM(total_recettes) as total_recettes'),
                DB::raw('SUM(total_depenses) as total_depenses'),
                DB::raw('AVG(total_recettes) as moyenne_recettes'),
                DB::raw('AVG(total_depenses) as moyenne_depenses'),
                DB::raw('AVG(total_recettes - total_depenses) as moyenne_solde'),
                DB::raw('MAX(total_recettes) as max_recettes'),
                DB::raw('MIN(total_recettes) as min_recettes'),
                DB::raw('COUNT(DISTINCT budget_annee) as total_annees'),
                DB::raw('COUNT(DISTINCT centre_id) as total_centres'),
                DB::raw('COUNT(*) as total_entries'),
            ])
            ->first();
        
        $totalRecettes = (float) ($aggregates->total_recettes ?? 0);
        $totalDepenses = (float) ($aggregates->total_depenses ?? 0);
        
        $this->stats = [
            'total_recettes' => $totalRecettes,
            'total_depenses' => $totalDepenses,
            'solde_global' => $totalRecettes - $totalDepenses,
            'taux_depenses' => $this->calculatePercentage($totalDepenses, $totalRecettes),
            'moyenne_recettes' => (float) ($aggregates->moyenne_recettes ?? 0),
            'moyenne_depenses' => (float) ($aggregates->moyenne_depenses ?? 0),
            'moyenne_solde' => (float) ($aggregates->moyenne_solde ?? 0),
            'max_recettes' => (float) ($aggregates->max_recettes ?? 0),
            'min_recettes' => (float) ($aggregates->min_recettes ?? 0),
            'total_annees' => (int) ($aggregates->total_annees ?? 0),
            'total_centres' => (int) ($aggregates->total_centres ?? 0),
            'total_entries' => (int) ($aggregates->total_entries ?? 0),
        ];
    }
    
    private function loadCentresStats(): void
    {
        $this->centresStats = RessourceFinanciere::with('centre:id,denomination')
            ->select([
                'centre_id',
                DB::raw('SUM(total_recettes) as total_recettes'),
                DB::raw('SUM(total_depenses) as total_depenses'),
                DB::raw('SUM(total_recettes - total_depenses) as solde'),
                DB::raw('AVG(total_recettes) as moyenne_recettes'),
                DB::raw('COUNT(*) as nombre_entrees'),
            ])
            ->groupBy('centre_id')
            ->orderByDesc('total_recettes')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                $recettes = (float) $item->total_recettes;
                $depenses = (float) $item->total_depenses;
                
                return [
                    'centre_id' => $item->centre_id,
                    'centre_name' => $item->centre?->denomination ?? 'Inconnu',
                    'total_recettes' => $recettes,
                    'total_depenses' => $depenses,
                    'solde' => (float) $item->solde,
                    'moyenne_recettes' => (float) $item->moyenne_recettes,
                    'nombre_entrees' => (int) $item->nombre_entrees,
                    'taux_depenses' => $this->calculatePercentage($depenses, $recettes),
                    'efficiency' => $recettes > 0 ? (($recettes - $depenses) / $recettes) * 100 : 0,
                ];
            })
            ->toArray();
    }
    
    private function loadEvolutionStats(): void
    {
        $this->evolutionStats = RessourceFinanciere::select([
                'budget_annee',
                DB::raw('SUM(total_recettes) as total_recettes'),
                DB::raw('SUM(total_depenses) as total_depenses'),
                DB::raw('SUM(total_recettes - total_depenses) as solde'),
                DB::raw('AVG(total_recettes) as moyenne_recettes'),
                DB::raw('AVG(total_depenses) as moyenne_depenses'),
                DB::raw('COUNT(DISTINCT centre_id) as nombre_centres'),
            ])
            ->groupBy('budget_annee')
            ->orderBy('budget_annee')
            ->get()
            ->map(function ($item) {
                $recettes = (float) $item->total_recettes;
                $depenses = (float) $item->total_depenses;
                
                return [
                    'annee' => (int) $item->budget_annee,
                    'total_recettes' => $recettes,
                    'total_depenses' => $depenses,
                    'solde' => (float) $item->solde,
                    'moyenne_recettes' => (float) $item->moyenne_recettes,
                    'moyenne_depenses' => (float) $item->moyenne_depenses,
                    'nombre_centres' => (int) $item->nombre_centres,
                    'taux_depenses' => $this->calculatePercentage($depenses, $recettes),
                ];
            })
            ->toArray();
    }
    
    // CHART 1: Bar Chart - Annual Comparison
    private function loadBarChart(): void
    {
        if (count($this->evolutionStats) === 0) {
            $this->barChart = [
                'labels' => [],
                'datasets' => []
            ];
            return;
        }
        
        $labels = array_column($this->evolutionStats, 'annee');
        $recettes = array_column($this->evolutionStats, 'total_recettes');
        $depenses = array_column($this->evolutionStats, 'total_depenses');
        $soldes = array_column($this->evolutionStats, 'solde');
        
        $this->barChart = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Recettes',
                    'data' => $recettes,
                    'backgroundColor' => self::CHART_COLORS['success'][0],
                    'borderColor' => self::CHART_COLORS['success'][1],
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
                [
                    'label' => 'Dépenses',
                    'data' => $depenses,
                    'backgroundColor' => self::CHART_COLORS['danger'][0],
                    'borderColor' => self::CHART_COLORS['danger'][1],
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
                [
                    'label' => 'Solde',
                    'data' => $soldes,
                    'backgroundColor' => self::CHART_COLORS['primary'][0],
                    'borderColor' => self::CHART_COLORS['primary'][1],
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ]
            ]
        ];
    }
    
    // CHART 2: Doughnut Chart - Budget Category Distribution
    private function loadDoughnutChart(): void
    {
        // Categorize by budget size
        $categories = RessourceFinanciere::select([
                DB::raw('CASE 
                    WHEN total_recettes <= 50000 THEN "Petit Budget"
                    WHEN total_recettes <= 200000 THEN "Moyen Budget"
                    WHEN total_recettes <= 500000 THEN "Grand Budget"
                    ELSE "Très Grand Budget"
                END as categorie'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_recettes) as total_recettes'),
                DB::raw('SUM(total_depenses) as total_depenses'),
                DB::raw('SUM(total_recettes - total_depenses) as total_solde'),
            ])
            ->groupBy(DB::raw('CASE 
                WHEN total_recettes <= 50000 THEN "Petit Budget"
                WHEN total_recettes <= 200000 THEN "Moyen Budget"
                WHEN total_recettes <= 500000 THEN "Grand Budget"
                ELSE "Très Grand Budget"
            END'))
            ->orderBy('total_recettes', 'desc')
            ->get();
        
        $labels = $categories->pluck('categorie')->toArray();
        $data = $categories->pluck('count')->map(fn($v) => (int) $v)->toArray();
        $recettesData = $categories->pluck('total_recettes')->map(fn($v) => (float) $v)->toArray();
        $soldeData = $categories->pluck('total_solde')->map(fn($v) => (float) $v)->toArray();
        
        $this->doughnutChart = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre d\'entrées',
                    'data' => $data,
                    'backgroundColor' => [
                        self::CHART_COLORS['primary'][0],
                        self::CHART_COLORS['success'][0],
                        self::CHART_COLORS['warning'][0],
                        self::CHART_COLORS['purple'][0],
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 3,
                    'hoverOffset' => 20,
                ]
            ],
            'details' => [
                'total_recettes' => $recettesData,
                'total_solde' => $soldeData,
            ]
        ];
    }
    
    // CHART 3: Pie Chart - Center Performance Distribution
    private function loadPieChart(): void
    {
        $centers = collect($this->centresStats);
        
        if ($centers->isEmpty()) {
            $this->pieChart = [
                'labels' => [],
                'datasets' => []
            ];
            return;
        }
        
        // Categorize centers by efficiency
        $performanceCategories = [
            'Excellente' => $centers->where('efficiency', '>', 20)->count(),
            'Bonne' => $centers->whereBetween('efficiency', [10, 20])->count(),
            'Moyenne' => $centers->whereBetween('efficiency', [0, 10])->count(),
            'Déficitaire' => $centers->where('efficiency', '<', 0)->count(),
        ];
        
        // Filter out empty categories
        $performanceCategories = array_filter($performanceCategories);
        
        $this->pieChart = [
            'labels' => array_keys($performanceCategories),
            'datasets' => [
                [
                    'label' => 'Performance des Centres',
                    'data' => array_values($performanceCategories),
                    'backgroundColor' => [
                        self::CHART_COLORS['success'][0], // Excellente
                        self::CHART_COLORS['primary'][0], // Bonne
                        self::CHART_COLORS['warning'][0], // Moyenne
                        self::CHART_COLORS['danger'][0],  // Déficitaire
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 3,
                    'hoverOffset' => 15,
                ]
            ]
        ];
    }
    
    // CHART 4: Line Chart - Trend Analysis
    private function loadLineChart(): void
    {
        if (count($this->evolutionStats) === 0) {
            $this->lineChart = [
                'labels' => [],
                'datasets' => []
            ];
            return;
        }
        
        $labels = array_column($this->evolutionStats, 'annee');
        $recettes = array_column($this->evolutionStats, 'total_recettes');
        $depenses = array_column($this->evolutionStats, 'total_depenses');
        $tauxDepenses = array_column($this->evolutionStats, 'taux_depenses');
        
        // Calculate moving averages for smoother trend lines
        $movingAvgRecettes = $this->calculateMovingAverage($recettes, 2);
        $movingAvgDepenses = $this->calculateMovingAverage($depenses, 2);
        
        $this->lineChart = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Recettes (Réel)',
                    'data' => $recettes,
                    'borderColor' => self::CHART_COLORS['success'][1],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'pointBackgroundColor' => self::CHART_COLORS['success'][0],
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                ],
                [
                    'label' => 'Recettes (Moyenne Mobile)',
                    'data' => $movingAvgRecettes,
                    'borderColor' => self::CHART_COLORS['success'][2],
                    'borderDash' => [5, 5],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Dépenses (Réel)',
                    'data' => $depenses,
                    'borderColor' => self::CHART_COLORS['danger'][1],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'pointBackgroundColor' => self::CHART_COLORS['danger'][0],
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 5,
                ],
                [
                    'label' => 'Dépenses (Moyenne Mobile)',
                    'data' => $movingAvgDepenses,
                    'borderColor' => self::CHART_COLORS['danger'][2],
                    'borderDash' => [5, 5],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Taux de Dépenses',
                    'data' => $tauxDepenses,
                    'borderColor' => self::CHART_COLORS['warning'][1],
                    'backgroundColor' => 'transparent',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'yAxisID' => 'y1',
                    'pointStyle' => 'circle',
                    'pointRadius' => 3,
                    'pointBackgroundColor' => self::CHART_COLORS['warning'][0],
                ]
            ]
        ];
    }
    
    private function calculatePercentage(float $value, float $total, int $decimals = 1): float
    {
        if ($total <= 0) {
            return 0.0;
        }
        
        return round(($value / $total) * 100, $decimals);
    }
    
    private function calculateMovingAverage(array $data, int $window = 2): array
    {
        $result = [];
        $count = count($data);
        
        for ($i = 0; $i < $count; $i++) {
            $start = max(0, $i - $window);
            $end = min($count - 1, $i + $window);
            $slice = array_slice($data, $start, $end - $start + 1);
            $result[] = array_sum($slice) / count($slice);
        }
        
        return $result;
    }

    public function render()
    {
        return view('livewire.reports');
    }
}