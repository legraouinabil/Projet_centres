<?php

namespace App\Livewire;

use App\Models\Partenaire;
use App\Models\Impact;
use App\Models\Centre;
use Livewire\Component;
use Livewire\WithPagination;

class Partenaires extends Component
{
    use WithPagination;

    public $nom, $evenements_organises, $participations_competitions, $trophies_gagnes, $impact_id;
    public $partenaireId, $isEditing = false, $showModal = false;
    public $search = '';
    public $selectedCentre = '';
    public $selectedActivite = '';

    protected $rules = [
        'nom' => 'required|string|max:255',
        'evenements_organises' => 'required|integer|min:0',
        'participations_competitions' => 'required|integer|min:0',
        'trophies_gagnes' => 'required|integer|min:0',
        'impact_id' => 'required|exists:impacts,id',
    ];

    public function render()
    {
        $partenaires = Partenaire::with(['impact.centre'])
            ->when($this->search, function ($query) {
                $query->where('nom', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCentre, function ($query) {
                $query->whereHas('impact.centre', function ($q) {
                    $q->where('id', $this->selectedCentre);
                });
            })
            ->when($this->selectedActivite, function ($query) {
                $query->whereHas('impact', function ($q) {
                    $q->where('type_activite', $this->selectedActivite);
                });
            })
            ->latest()
            ->paginate(10);

        $centres = Centre::all();
        $impacts = Impact::when($this->selectedCentre, function ($query) {
                $query->where('centre_id', $this->selectedCentre);
            })
            ->when($this->selectedActivite, function ($query) {
                $query->where('type_activite', $this->selectedActivite);
            })
            ->get();

        $activites = ['formation_professionnelle', 'animation_culturelle_sportive', 'handicap', 'eps'];

        $stats = [
            'total_partenaires' => $partenaires->total(),
            'total_evenements' => $partenaires->sum('evenements_organises'),
            'total_participations' => $partenaires->sum('participations_competitions'),
            'total_trophies' => $partenaires->sum('trophies_gagnes'),
        ];

        return view('livewire.partenaires', compact('partenaires', 'centres', 'impacts', 'activites', 'stats'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $partenaire = Partenaire::findOrFail($id);
        $this->partenaireId = $id;
        $this->nom = $partenaire->nom;
        $this->evenements_organises = $partenaire->evenements_organises;
        $this->participations_competitions = $partenaire->participations_competitions;
        $this->trophies_gagnes = $partenaire->trophies_gagnes;
        $this->impact_id = $partenaire->impact_id;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nom' => $this->nom,
            'evenements_organises' => $this->evenements_organises,
            'participations_competitions' => $this->participations_competitions,
            'trophies_gagnes' => $this->trophies_gagnes,
            'impact_id' => $this->impact_id,
        ];

        if ($this->isEditing) {
            Partenaire::find($this->partenaireId)->update($data);
            $message = 'Partenaire modifié avec succès!';
        } else {
            Partenaire::create($data);
            $message = 'Partenaire créé avec succès!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('show-toast', type: 'success', message: $message);
    }

    public function delete($id)
    {
        Partenaire::find($id)->delete();
        $this->dispatch('show-toast', type: 'success', message: 'Partenaire supprimé avec succès!');
    }

    private function resetForm()
    {
        $this->reset([
            'nom', 'evenements_organises', 'participations_competitions', 
            'trophies_gagnes', 'impact_id', 'partenaireId', 'isEditing'
        ]);
        $this->resetErrorBag();
    }

    public function updatedSelectedCentre()
    {
        $this->reset('selectedActivite');
    }

    public function getPerformanceColor($trophies)
    {
        return match(true) {
            $trophies >= 10 => 'green',
            $trophies >= 5 => 'blue',
            $trophies >= 1 => 'yellow',
            default => 'gray'
        };
    }
}