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

    // Export Ressources Humaines filtered list to PDF
    public function exportRessourcesHumaines(Request $request)
    {
        $query = RessourceHumaine::with('centre');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function($q) use ($s) {
                $q->where('nom_prenom', 'like', '%'.$s.'%')
                  ->orWhere('poste', 'like', '%'.$s.'%');
            });
        }

        if ($request->filled('centre_id')) {
            $query->where('centre_id', $request->input('centre_id'));
        }

        if ($request->filled('type_contrat')) {
            $query->where('type_contrat', $request->input('type_contrat'));
        }

        $rhs = $query->orderBy('nom_prenom')->get();

        $pdf = Pdf::loadView('exportressorce', ['rhs' => $rhs, 'filters' => $request->only(['search','centre_id','type_contrat'])]);
        return $pdf->download('ressources_humaines_'.now()->format('Ymd_His').'.pdf');
    }

    // Export Ressources Financières filtered list to PDF
    public function exportRessourcesFinancieres(Request $request)
    {
        $query = RessourceFinanciere::with('centre');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->whereHas('centre', function($q) use ($s) {
                $q->where('denomination', 'like', '%'.$s.'%')
                  ->orWhere('localisation', 'like', '%'.$s.'%');
            });
        }

        if ($request->filled('selectedYear')) {
            $query->where('budget_annee', $request->input('selectedYear'));
        }

        $rfs = $query->orderBy('budget_annee', 'desc')->get();

        $pdf = Pdf::loadView('exportfinance', ['rfs' => $rfs, 'filters' => $request->only(['search','selectedYear'])]);
        return $pdf->download('ressources_financieres_'.now()->format('Ymd_His').'.pdf');
    }

    // Export all centres list to PDF
    public function exportCentresPdf(Request $request)
    {
        $query = Centre::with('associations');

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function($q) use ($s) {
                $q->where('denomination', 'like', '%'.$s.'%')
                  ->orWhere('localisation', 'like', '%'.$s.'%');
            });
        }

        $centres = $query->orderBy('denomination')->get();

        $pdf = Pdf::loadView('exports.centres-pdf', ['centres' => $centres, 'date' => now()->format('d/m/Y')]);
        return $pdf->download('centres_'.now()->format('Ymd_His').'.pdf');
    }
}