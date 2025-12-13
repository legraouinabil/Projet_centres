<?php

namespace App\Livewire;

use App\Models\Centre;
use App\Models\RessourceHumaine;
use App\Models\RessourceFinanciere;
use App\Models\Impact;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Reports extends Component
{
    public $startDate;
    public $endDate;
    public $selectedDomaine = '';
    public $reportType = 'global';
    public $loading = false;

    public $globalStats = [];
    public $financialStats = [];
    public $performanceStats = [];
    public $centresPerformance = [];

    public function mount()
    {
        $this->startDate = now()->subYear()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->generateReports();
    }

    public function generateReports()
    {
        $this->loading = true;

        // Statistiques Globales
        $this->globalStats = [
            'total_centres' => Centre::count(),
            'total_personnel' => RessourceHumaine::count(),
            'total_beneficiaires' => Impact::sum(DB::raw('nombre_inscrits_hommes + nombre_inscrits_femmes')),
            'masse_salariale' => RessourceHumaine::sum('salaire'),
            'budget_total' => RessourceFinanciere::sum('total_recettes'),
        ];

        // Statistiques Financières
        $this->financialStats = RessourceFinanciere::select(
            DB::raw('SUM(total_recettes) as total_recettes'),
            DB::raw('SUM(total_depenses) as total_depenses'),
            DB::raw('AVG(total_recettes - total_depenses) as solde_moyen'),
            DB::raw('COUNT(DISTINCT centre_id) as centres_avec_budget')
        )->first()->toArray();

        // Performance par Domaine
        $this->performanceStats = Impact::groupBy('type_activite')
            ->selectRaw('
                type_activite,
                COUNT(*) as total_activites,
                SUM(nombre_inscrits_hommes + nombre_inscrits_femmes) as total_beneficiaires,
                AVG(taux_insertion) as taux_insertion_moyen,
                SUM(nombre_laureats) as total_laureats
            ')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->type_activite => [
                    'total_activites' => $item->total_activites,
                    'total_beneficiaires' => $item->total_beneficiaires,
                    'taux_insertion_moyen' => $item->taux_insertion_moyen,
                    'total_laureats' => $item->total_laureats,
                ]];
            });

        // Performance des Centres
        $this->centresPerformance = Centre::with(['impacts', 'ressourcesFinancieres'])
            ->when($this->selectedDomaine, function ($query) {
                $query->where('domaine_intervention', $this->selectedDomaine);
            })
            ->get()
            ->map(function ($centre) {
                $totalBeneficiaires = $centre->impacts->sum(function ($impact) {
                    return $impact->nombre_inscrits_hommes + $impact->nombre_inscrits_femmes;
                });

                $budget = $centre->ressourcesFinancieres->sum('total_recettes');
                $coutParBeneficiaire = $totalBeneficiaires > 0 ? $budget / $totalBeneficiaires : 0;

                return [
                    'id' => $centre->id,
                    'denomination' => $centre->denomination,
                    'domaine' => $centre->domaine_intervention,
                    'localisation' => $centre->localisation,
                    'total_beneficiaires' => $totalBeneficiaires,
                    'budget_total' => $budget,
                    'cout_par_beneficiaire' => $coutParBeneficiaire,
                    'taux_insertion_moyen' => $centre->impacts->avg('taux_insertion') ?? 0,
                ];
            })
            ->sortByDesc('total_beneficiaires')
            ->take(10);

        $this->loading = false;
    }

    public function exportPdf()
    {
        $this->dispatch('show-toast', type: 'info', message: 'Génération du PDF en cours...');
        // Logique d'export PDF à implémenter
    }

    public function exportExcel()
    {
        $this->dispatch('show-toast', type: 'info', message: 'Génération du Excel en cours...');
        // Logique d'export Excel à implémenter
    }

    public function render()
    {
        $domaines = [
            '' => 'Tous les domaines',
            'formation_professionnelle' => 'Formation Professionnelle',
            'animation_culturelle_sportive' => 'Animation Culturelle et Sportive',
            'handicap' => 'Handicap',
            'eps' => 'EPS'
        ];

        return view('livewire.reports', compact('domaines'));
    }

    public function getDomaineColor($domaine)
    {
        return match($domaine) {
            'formation_professionnelle' => 'green',
            'animation_culturelle_sportive' => 'blue',
            'handicap' => 'purple',
            'eps' => 'orange',
            default => 'gray'
        };
    }
}