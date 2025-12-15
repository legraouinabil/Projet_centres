<?php
// app/Http/Livewire/DossierManager.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Dossier;
use App\Models\Document;
use App\Models\Association;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DossierManager extends Component
{
    use WithPagination, WithFileUploads;

    public $dossiers;
    public $selectedDossier = null;
    public $documents = [];
    public $newDocument;
    public $showDossierModal = false;
    public $showDocumentModal = false;
    
    // Dossier form fields
    public $dossierId;
    public $title = '';
    public $description = '';
    public $reference = '';
    public $status = 'draft';
    public $assigned_to = '';
    public $due_date = '';
    public $association_id = ''; // Ajout du champ association

    // Document form fields
    public $documentId;
    public $documentName = '';
    public $documentDescription = '';
    public $documentFile;

    protected $rules = [
        'title' => 'required|min:3',
        'reference' => 'required|unique:dossiers,reference',
        'status' => 'required|in:draft,active,completed,archived',
        'due_date' => 'nullable|date',
        'association_id' => 'required|exists:associations,id', // Validation pour association
    ];

    public function mount()
    {
        $this->loadDossiers();
    }

    public function loadDossiers()
    {
        $this->dossiers = Dossier::with(['documents', 'creator', 'assignee', 'association'])
            ->latest()
            ->get();
    }

    public function selectDossier($dossierId)
    {
        $this->selectedDossier = Dossier::with(['documents.uploader', 'association'])->find($dossierId);
        $this->documents = $this->selectedDossier->documents ?? [];
    }

    public function createDossier()
    {
        $this->resetDossierForm();
        $this->showDossierModal = true;
    }

    public function editDossier($dossierId)
    {
        $dossier = Dossier::find($dossierId);
        $this->dossierId = $dossier->id;
        $this->title = $dossier->title;
        $this->description = $dossier->description;
        $this->reference = $dossier->reference;
        $this->status = $dossier->status;
        $this->assigned_to = $dossier->assigned_to;
        $this->due_date = $dossier->due_date?->format('Y-m-d');
        $this->association_id = $dossier->association_id; // Récupérer l'association
        $this->showDossierModal = true;
    }

    public function saveDossier()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'reference' => $this->reference,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to ?: null,
            'due_date' => $this->due_date ?: null,
            'association_id' => $this->association_id, // Sauvegarder l'association
            'created_by' => Auth::id(),
        ];

        if ($this->dossierId) {
            $dossier = Dossier::find($this->dossierId);
            $dossier->update($data);
            session()->flash('message', 'Dossier mis à jour avec succès !');
        } else {
            Dossier::create($data);
            session()->flash('message', 'Dossier créé avec succès !');
        }

        $this->showDossierModal = false;
        $this->loadDossiers();
        $this->resetDossierForm();
    }

    public function deleteDossier($dossierId)
    {
        Dossier::find($dossierId)->delete();
        $this->loadDossiers();
        $this->selectedDossier = null;
        session()->flash('message', 'Dossier supprimé avec succès !');
    }

    public function addDocument()
    {
        $this->resetDocumentForm();
        $this->showDocumentModal = true;
    }

    public function saveDocument()
    {
        $this->validate([
            'documentName' => 'required|min:3',
            'documentFile' => 'required|file|max:10240', // 10MB max
        ]);

        $filePath = $this->documentFile->store('documents', 'public');

        Document::create([
            'dossier_id' => $this->selectedDossier->id,
            'name' => $this->documentName,
            'file_path' => $filePath,
            'file_size' => $this->documentFile->getSize(),
            'mime_type' => $this->documentFile->getMimeType(),
            'description' => $this->documentDescription,
            'uploaded_by' => Auth::id(),
        ]);

        $this->showDocumentModal = false;
        $this->selectDossier($this->selectedDossier->id);
        $this->resetDocumentForm();
        session()->flash('message', 'Document téléchargé avec succès !');
    }

    public function deleteDocument($documentId)
    {
        $document = Document::find($documentId);
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        
        $this->selectDossier($this->selectedDossier->id);
        session()->flash('message', 'Document supprimé avec succès !');
    }

    public function downloadDocument($documentId)
    {
        $document = Document::find($documentId);
        return Storage::disk('public')->download($document->file_path, $document->name);
    }

    private function resetDossierForm()
    {
        $this->dossierId = null;
        $this->title = '';
        $this->description = '';
        $this->reference = '';
        $this->status = 'draft';
        $this->assigned_to = '';
        $this->due_date = '';
        $this->association_id = '';
    }

    private function resetDocumentForm()
    {
        $this->documentId = null;
        $this->documentName = '';
        $this->documentDescription = '';
        $this->documentFile = null;
    }

    public function render()
    {
        return view('livewire.dossier-manager', [
            'associations' => Association::active()->orderBy('nom_de_l_asso')->get(),
        ]);
    }
}