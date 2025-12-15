<?php

namespace App\Livewire;

use App\Models\RessourceHumaine;
use App\Models\Centre;
use Livewire\Component;
use Livewire\WithPagination;

class RessourcesHumaines extends Component
{
    use WithPagination;

    public $poste, $nom_prenom, $salaire, $type_contrat, $centre_id;
    public $rhId, $isEditing = false, $showModal = false;
    public $search = '';

    protected $rules = [
        'poste' => 'required|string|max:255',
        'nom_prenom' => 'required|string|max:255',
        'salaire' => 'required|numeric|min:0',
        'type_contrat' => 'required|string|max:255',
        'centre_id' => 'required|exists:centres,id',
    ];

    public function render()
    {
        $rhs = RessourceHumaine::with('centre')
            ->when($this->search, function ($query) {
                $query->where('nom_prenom', 'like', '%' . $this->search . '%')
                      ->orWhere('poste', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $centres = Centre::all();

        return view('livewire.ressources-humaines', compact('rhs', 'centres'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $rh = RessourceHumaine::findOrFail($id);
        $this->rhId = $id;
        $this->fill($rh->toArray());
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = $this->only(['poste', 'nom_prenom', 'salaire', 'type_contrat', 'centre_id']);

        if ($this->isEditing) {
            RessourceHumaine::find($this->rhId)->update($data);
            $message = 'Personnel modifié avec succès!';
        } else {
            RessourceHumaine::create($data);
            $message = 'Personnel créé avec succès!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('show-toast', type: 'success', message: $message);
    }

    public function delete($id)
    {
        RessourceHumaine::find($id)->delete();
        $this->dispatch('show-toast', type: 'success', message: 'Personnel supprimé avec succès!');
    }

    private function resetForm()
    {
        $this->reset();
        $this->resetErrorBag();
    }
}