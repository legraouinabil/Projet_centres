<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $timeout = config('session.inactivity_timeout', 30) * 60;
            $last = session('last_activity_time');
            if ($last && (time() - $last) > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['message' => 'You have been logged out due to inactivity.']);
            }
            session(['last_activity_time' => time()]);
        }
        return $next($request);
    }
}
