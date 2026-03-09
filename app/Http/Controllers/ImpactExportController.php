<?php
namespace App\Http\Controllers;

use App\Models\ImpactBeneficiaire;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ImpactExportController extends Controller
{
    public function export($id)
    {
        $impact = ImpactBeneficiaire::with([
            'centre', 'formationPro', 'animation', 'handicap', 'eps', 'partenaires'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('exportimpact', ['impact' => $impact]);

        return $pdf->download('impact_'.$impact->id.'.pdf');
    }

    // Export a filtered list of impacts as PDF
    public function exportList(Request $request)
    {
        $query = ImpactBeneficiaire::with(['centre', 'formationPro', 'animation', 'handicap', 'eps', 'partenaires']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('intitule_filiere_discipline', 'like', '%'.$search.'%')
                  ->orWhereHas('centre', function($q) use ($search) {
                      $q->where('denomination', 'like', '%'.$search.'%');
                  });
            });
        }

        if ($request->filled('centre_id')) {
            $query->where('centre_id', $request->input('centre_id'));
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->input('annee'));
        }

        if ($request->filled('domaine')) {
            $query->where('domaine', $request->input('domaine'));
        }

        $impacts = $query->orderBy('annee', 'desc')->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('exportimpact-list', ['impacts' => $impacts, 'filters' => $request->only(['search','centre_id','annee','domaine'])]);

        return $pdf->download('impacts_filtered_'.now()->format('Ymd_His').'.pdf');
    }
}
