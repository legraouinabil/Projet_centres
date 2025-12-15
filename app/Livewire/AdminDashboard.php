<?php
// app/Http/Livewire/AdminDashboard.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminDashboard extends Component
{
    public $user;
    public $stats = [];
    public $recentUsers = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadStats();
        $this->loadRecentUsers();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'manager_users' => User::where('role', 'manager')->count(),
            'regular_users' => User::where('role', 'user')->count(),
        ];
    }

    public function loadRecentUsers()
    {
        $this->recentUsers = User::latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}