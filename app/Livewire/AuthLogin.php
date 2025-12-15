<?php
// app/Http/Livewire/AuthLogin.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthLogin extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function render()
    {

        return view('livewire.auth-login');
    }

    public function login()
    {
        $this->validate();

        // Rate limiting
        $throttleKey = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        // Redirect based on role
        $user = Auth::user();

        session()->regenerate();
        $user->update(['last_login_at' => now()]);
         return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'user'  => redirect()->route('dashboard'),
                default => redirect()->route('home'),
            };
    }

    private function getRedirectRoute($user)
    {
        return match ($user->role) {
            'admin' => '/admin/dashboard',
            'manager' => '/manager/dashboard',
            'user' => '/',
        };
    }

    public function mount()
    {
        if (auth()->check()) {
            $user = Auth::user();

            session()->regenerate();

            return redirect()->intended($this->getRedirectRoute($user));
        }
    }
}
