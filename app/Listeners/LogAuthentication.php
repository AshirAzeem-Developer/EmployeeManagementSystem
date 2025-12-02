<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Helpers\LogActivity;

class LogAuthentication
{
    public function handleLogin(Login $event)
    {
        LogActivity::addToLog('Login', 'User logged in.');
    }

    public function handleLogout(Logout $event)
    {
        LogActivity::addToLog('Logout', 'User logged out.');
    }

    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [LogAuthentication::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [LogAuthentication::class, 'handleLogout']
        );
    }
}
