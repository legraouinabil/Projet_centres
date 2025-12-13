<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role = 'user';
    public $phone;
    public $department;
    public $position;
    public $is_active = true;
    public $userId;
    public $isEditing = false;
    public $showModal = false;
    public $search = '';
    public $roleFilter = '';
    public $departmentFilter = '';
    public $statusFilter = '';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,manager,user',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];

        if ($this->isEditing) {
            $rules['email'] = 'required|string|email|max-255|unique:users,email,' . $this->userId;
            $rules['password'] = ['nullable', 'confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }

    // Get available roles
    public function getRolesProperty()
    {
        return [
            'admin' => 'Administrateur',
            'manager' => 'Gestionnaire',
            'user' => 'Utilisateur',
        ];
    }

    public function render()
    {
        $users = User::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
        })
        ->when($this->roleFilter, function ($query) {
            $query->where('role', $this->roleFilter);
        })
        ->when($this->departmentFilter, function ($query) {
            $query->where('department', 'like', '%' . $this->departmentFilter . '%');
        })
        ->when($this->statusFilter !== '', function ($query) {
            $query->where('is_active', $this->statusFilter);
        })
        ->latest()
        ->paginate(10);

        $departments = User::whereNotNull('department')->distinct()->pluck('department');

        return view('livewire.user-manager', [
            'users' => $users,
            'departments' => $departments,
        ]);
    }

    public function create()
    {
        $this->reset();
        $this->is_active = true;
        $this->role = 'user';
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->phone = $user->phone;
        $this->department = $user->department;
        $this->position = $user->position;
        $this->is_active = $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'department' => $this->department,
            'position' => $this->position,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            User::find($this->userId)->update($userData);
            session()->flash('message', 'Utilisateur mis à jour avec succès.');
        } else {
            User::create($userData);
            session()->flash('message', 'Utilisateur créé avec succès.');
        }

        $this->reset();
        $this->showModal = false;
    }

    public function delete($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Utilisateur supprimé avec succès.');
    }

    public function toggleStatus($id)
    {
        $user = User::find($id);
        $user->update(['is_active' => !$user->is_active]);
        
        session()->flash('message', 'Statut utilisateur mis à jour avec succès.');
    }

    public function closeModal()
    {
        $this->reset();
        $this->showModal = false;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->roleFilter = '';
        $this->departmentFilter = '';
        $this->statusFilter = '';
    }
}