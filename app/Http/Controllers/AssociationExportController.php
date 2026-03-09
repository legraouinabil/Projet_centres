<?php

namespace App\Http\Controllers;

use App\Models\Association;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AssociationExportController extends Controller
{
    /**
     * Export a filtered list of associations to PDF
     */
    public function exportList(Request $request)
    {
        $query = Association::with(['secteur', 'district', 'creator', 'centres']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom_de_l_asso', 'like', '%' . $search . '%')
                    ->orWhere('nom_asso_ar', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('numero_agrement', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status'));
        }

        if ($request->filled('secteur')) {
            $query->where('secteur_id', $request->input('secteur'));
        }

        if ($request->filled('district')) {
            $query->where('districts_id', $request->input('district'));
        }

        $associations = $query->orderBy('nom_de_l_asso')->get();

        $pdf = Pdf::loadView('export-association-list', ['associations' => $associations, 'filters' => $request->only(['search','status','secteur','district'])]);

        return $pdf->download('associations_filtered_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Optional: export single association
     */
    public function export($id)
    {
        $association = Association::with(['secteur', 'district', 'creator', 'centres'])->findOrFail($id);
        $pdf = Pdf::loadView('export-association-single', ['association' => $association]);
        return $pdf->download('association_' . $association->id . '.pdf');
    }
}
