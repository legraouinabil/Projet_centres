<?php

namespace App\Http\Controllers;

use App\Models\Centre;
use App\Models\RessourceHumaine;
use App\Models\RessourceFinanciere;
use App\Models\Impact;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        $domaine = $request->get('domaine', '');
        $type = $request->get('type', 'global');
        
        // Récupérer les données avec les mêmes filtres
        $data = $this->getReportData($domaine);
        
        $pdf = Pdf::loadView('exports.reports-pdf', [
            'data' => $data,
            'domaine' => $domaine,
            'type' => $type,
            'date' => now()->format('d/m/Y'),
            'domaineLabel' => $this->getDomaineLabel($domaine),
            'statLabels' => $this->getStatLabels()
        ]);
        
        return $pdf->download('rapport-centres-' . now()->format('Y-m-d') . '.pdf');
    }
    
    public function exportExcel(Request $request)
    {
        $domaine = $request->get('domaine', '');
        $type = $request->get('type', 'global');
        
        // Récupérer les données
        $data = $this->getReportData($domaine);
        
        $filename = 'rapport-centres-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // En-tête
            fputcsv($file, ['RAPPORT DES CENTRES - ' . now()->format('d/m/Y')]);
            fputcsv($file, []); // Ligne vide
            
            // Statistiques
            fputcsv($file, ['STATISTIQUES GLOBALES']);
            foreach ($data['globalStats'] as $key => $value) {
                $label = $this->getStatLabel($key);
                $formattedValue = $this->formatStatValue($key, $value);
                fputcsv($file, [$label, $formattedValue]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function getReportData($domaine)
    {
        $globalStats = [
            'total_centres' => Centre::when($domaine, function($query) use ($domaine) {
                return $query->where('domaine_intervention', $domaine);
            })->count(),
            
            'total_personnel' => RessourceHumaine::when($domaine, function($query) use ($domaine) {
                return $query->whereHas('centre', function($q) use ($domaine) {
                    $q->where('domaine_intervention', $domaine);
                });
            })->count(),
            
            'total_beneficiaires' => Impact::when($domaine, function($query) use ($domaine) {
                return $query->whereHas('centre', function($q) use ($domaine) {
                    $q->where('domaine_intervention', $domaine);
                });
            })->sum(DB::raw('nombre_inscrits_hommes + nombre_inscrits_femmes')),
            
            'masse_salariale' => RessourceHumaine::when($domaine, function($query) use ($domaine) {
                return $query->whereHas('centre', function($q) use ($domaine) {
                    $q->where('domaine_intervention', $domaine);
                });
            })->sum('salaire'),
            
            'budget_total' => RessourceFinanciere::when($domaine, function($query) use ($domaine) {
                return $query->whereHas('centre', function($q) use ($domaine) {
                    $q->where('domaine_intervention', $domaine);
                });
            })->sum('total_recettes'),
        ];
        
        return [
            'globalStats' => $globalStats,
            'domaine' => $domaine
        ];
    }
    
    private function getDomaineLabel($domaine)
    {
        return match($domaine) {
            'formation_professionnelle' => 'Formation Professionnelle',
            'animation_culturelle_sportive' => 'Animation Culturelle et Sportive',
            'handicap' => 'Handicap',
            'eps' => 'EPS',
            '' => 'Tous les domaines',
            default => $domaine
        };
    }
    
    private function getStatLabel($key)
    {
        return match($key) {
            'total_centres' => 'Nombre de Centres',
            'total_personnel' => 'Effectif du Personnel',
            'total_beneficiaires' => 'Total Bénéficiaires',
            'masse_salariale' => 'Masse Salariale',
            'budget_total' => 'Budget Total',
            default => $key
        };
    }
    
    private function getStatLabels()
    {
        return [
            'total_centres' => 'Nombre de Centres',
            'total_personnel' => 'Effectif du Personnel',
            'total_beneficiaires' => 'Total Bénéficiaires',
            'masse_salariale' => 'Masse Salariale',
            'budget_total' => 'Budget Total'
        ];
    }
    
    private function formatStatValue($key, $value)
    {
        return match($key) {
            'masse_salariale', 'budget_total' => number_format($value, 0, ',', ' ') . ' DH',
            'total_beneficiaires' => number_format($value, 0, ',', ' '),
            default => $value
        };
    }
}