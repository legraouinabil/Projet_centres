<?php

namespace App\Livewire;

use App\Models\RessourceFinanciere;
use App\Models\Centre;
use Livewire\Component;
use Livewire\WithPagination;

class RessourcesFinancieres extends Component
{
    use WithPagination;

    public $budget_annee, $total_depenses, $total_recettes, $centre_id;
    public $rfId, $isEditing = false, $showModal = false;
    public $search = '';
    public $selectedYear;

    protected $rules = [
        'budget_annee' => 'required|integer|min:2000|max:2030',
        'total_depenses' => 'required|numeric|min:0',
        'total_recettes' => 'required|numeric|min:0',
        'centre_id' => 'required|exists:centres,id',
    ];

    public function mount()
    {
       // $this->selectedYear = date('Y');
    }

    public function render()
    {
        $rfs = RessourceFinanciere::with('centre')
            ->when($this->search, function ($query) {
                $query->whereHas('centre', function ($q) {
                    $q->where('denomination', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedYear, function ($query) {
                $query->where('budget_annee', $this->selectedYear);
            })
            ->latest()
            ->paginate(10);

        $centres = Centre::all();
        $years = RessourceFinanciere::distinct()->pluck('budget_annee')->sortDesc();

        $stats = [
            'total_recettes' => $rfs->sum('total_recettes'),
            'total_depenses' => $rfs->sum('total_depenses'),
            'solde_total' => $rfs->sum('total_recettes') - $rfs->sum('total_depenses'),
        ];

        return view('livewire.ressources-financieres', compact('rfs', 'centres', 'years', 'stats'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $rf = RessourceFinanciere::findOrFail($id);
        $this->rfId = $id;
        $this->fill($rf->toArray());
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = $this->only(['budget_annee', 'total_depenses', 'total_recettes', 'centre_id']);

        if ($this->isEditing) {
            RessourceFinanciere::find($this->rfId)->update($data);
            $message = 'Ressource financière modifiée avec succès!';
        } else {
            RessourceFinanciere::create($data);
            $message = 'Ressource financière créée avec succès!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('show-toast', type: 'success', message: $message);
    }

    public function delete($id)
    {
        RessourceFinanciere::find($id)->delete();
        $this->dispatch('show-toast', type: 'success', message: 'Ressource financière supprimée avec succès!');
    }

    private function resetForm()
    {
        $this->reset(['budget_annee', 'total_depenses', 'total_recettes', 'centre_id', 'rfId', 'isEditing']);
        $this->resetErrorBag();
    }
}