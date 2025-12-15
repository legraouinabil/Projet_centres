<?php

namespace App\Livewire;

use App\Models\Centre;
use App\Models\Association;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Centres extends Component
{
    use WithPagination;

    public $denomination, $domaine_intervention, $localisation, $superficie;
    public $objectifs, $composantes, $nature_foncier, $cout_construction, $cout_equipement;
    public $centreId, $isEditing = false, $showModal = false;
    public $search = '';
    public $association_ids = [];

    protected $rules = [
        'denomination' => 'required|string|max:255',
        'domaine_intervention' => 'required|in:formation_professionnelle,animation_culturelle_sportive,handicap,eps',
        'localisation' => 'required|string|max:255',
        'superficie' => 'required|numeric|min:0',
        'objectifs' => 'required|string',
        'composantes' => 'required|string',
        'nature_foncier' => 'required|string|max:255',
        'cout_construction' => 'required|numeric|min:0',
        'cout_equipement' => 'required|numeric|min:0',
        'association_ids' => 'array',
        'association_ids.*' => 'exists:associations,id',
    ];

    public function render()
    {
        $centres = Centre::with('associations')->when($this->search, function ($query) {
            $query->where('denomination', 'like', '%' . $this->search . '%')
                  ->orWhere('localisation', 'like', '%' . $this->search . '%');
        })->latest()->paginate(10);

        $associations = Association::orderBy('nom_de_l_asso')->get();

        return view('livewire.centres', compact('centres', 'associations'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $centre = Centre::findOrFail($id);
        $this->centreId = $id;
        $this->fill($centre->toArray());
        $this->isEditing = true;
        $this->association_ids = $centre->associations()->pluck('associations.id')->toArray();
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = $this->only([
            'denomination', 'domaine_intervention', 'localisation', 'superficie',
            'objectifs', 'composantes', 'nature_foncier', 'cout_construction', 'cout_equipement'
        ]);

        if ($this->isEditing) {
            $centre = Centre::find($this->centreId);
            $centre->update($data);
            $centre->associations()->sync($this->association_ids ?? []);
            $message = 'Centre modifié avec succès!';
        } else {
            $centre = Centre::create($data);
            $centre->associations()->sync($this->association_ids ?? []);
            $message = 'Centre créé avec succès!';
        }
        // flash session message for UI feedback
        session()->flash('message', $message);

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('show-toast', type: 'success', message: $message);
    }

    public function delete($id)
    {
        Centre::find($id)->delete();
        session()->flash('message', 'Centre supprimé avec succès!');
        $this->dispatch('show-toast', type: 'success', message: 'Centre supprimé avec succès!');
    }

    private function resetForm()
    {
        $this->reset();
        $this->resetErrorBag();
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