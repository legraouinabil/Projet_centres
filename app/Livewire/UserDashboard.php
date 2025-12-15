<?php
// app/Http/Livewire/UserDashboard.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    public $user;
    public $stats = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_projects' => 12,
            'completed_tasks' => 45,
            'pending_tasks' => 8,
            'team_members' => 5,
        ];
    }

    public function render()
    {
        return view('livewire.user-dashboard');
    }
}