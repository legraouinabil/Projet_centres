<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use App\Helpers\ActivityLogger;

class LogAuthActivity
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event instanceof Login) {
            ActivityLogger::log('auth.login', $event->user, ['guard' => $event->guard ?? null]);
        } elseif ($event instanceof Logout) {
            ActivityLogger::log('auth.logout', $event->user ?? null, []);
        } elseif ($event instanceof Registered) {
            ActivityLogger::log('auth.registered', $event->user, []);
        } elseif ($event instanceof Failed) {
            $props = [];
            if (is_array($event->credentials) && isset($event->credentials['email'])) {
                $props['email'] = $event->credentials['email'];
            }
            ActivityLogger::log('auth.failed', null, $props);
        } elseif ($event instanceof PasswordReset) {
            ActivityLogger::log('auth.password_reset', $event->user ?? null, []);
        }
    }
}
