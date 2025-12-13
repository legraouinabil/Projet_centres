<?php

namespace App\Livewire;

use App\Models\Gestionnaire;
use App\Models\Centre;
use Livewire\Component;
use Livewire\WithPagination;

class Gestionnaires extends Component
{
    use WithPagination;

    public $association, $recepisse_definitif, $liste_membres, $liasse_fiscale, $centre_id;
    public $gestionnaireId, $isEditing = false, $showModal = false;
    public $search = '';
    public $newMember = '';

    protected $rules = [
        'association' => 'required|string|max:255',
        'recepisse_definitif' => 'required|string|max:255',
        'liste_membres' => 'required|array|min:1',
        'liasse_fiscale' => 'required|string|max:255',
        'centre_id' => 'required|exists:centres,id|unique:gestionnaires,centre_id',
    ];

    public function mount()
    {
        $this->liste_membres = [];
    }

    public function render()
    {
        $gestionnaires = Gestionnaire::with('centre')
            ->when($this->search, function ($query) {
                $query->where('association', 'like', '%' . $this->search . '%')
                      ->orWhere('recepisse_definitif', 'like', '%' . $this->search . '%')
                      ->orWhereHas('centre', function ($q) {
                          $q->where('denomination', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        $centres = Centre::whereNotIn('id', Gestionnaire::pluck('centre_id'))->get();

        return view('livewire.gestionnaires', compact('gestionnaires', 'centres'));
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $gestionnaire = Gestionnaire::findOrFail($id);
        $this->gestionnaireId = $id;
        $this->association = $gestionnaire->association;
        $this->recepisse_definitif = $gestionnaire->recepisse_definitif;
        $this->liste_membres = $gestionnaire->liste_membres;
        $this->liasse_fiscale = $gestionnaire->liasse_fiscale;
        $this->centre_id = $gestionnaire->centre_id;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'association' => $this->association,
            'recepisse_definitif' => $this->recepisse_definitif,
            'liste_membres' => $this->liste_membres,
            'liasse_fiscale' => $this->liasse_fiscale,
            'centre_id' => $this->centre_id,
        ];

        if ($this->isEditing) {
            // Pour l'édition, on enlève la règle unique
            unset($this->rules['centre_id']);
            $this->validate(['centre_id' => 'required|exists:centres,id']);
            
            Gestionnaire::find($this->gestionnaireId)->update($data);
            $message = 'Gestionnaire modifié avec succès!';
        } else {
            Gestionnaire::create($data);
            $message = 'Gestionnaire créé avec succès!';
        }

        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('show-toast', type: 'success', message: $message);
    }

    public function delete($id)
    {
        Gestionnaire::find($id)->delete();
        $this->dispatch('show-toast', type: 'success', message: 'Gestionnaire supprimé avec succès!');
    }

    public function addMember()
    {
        if (!empty($this->newMember)) {
            $this->liste_membres[] = trim($this->newMember);
            $this->newMember = '';
        }
    }

    public function removeMember($index)
    {
        unset($this->liste_membres[$index]);
        $this->liste_membres = array_values($this->liste_membres);
    }

    private function resetForm()
    {
        $this->reset([
            'association', 'recepisse_definitif', 'liste_membres', 'liasse_fiscale', 
            'centre_id', 'gestionnaireId', 'isEditing', 'newMember'
        ]);
        $this->liste_membres = [];
        $this->resetErrorBag();
    }
}