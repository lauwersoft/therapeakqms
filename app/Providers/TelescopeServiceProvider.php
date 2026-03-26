<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($entry->type === 'request') {
                $uri = $entry->content['uri'] ?? '';

                // Always record login attempts (detect brute force / hacking)
                if (str_contains($uri, '/login')) {
                    return true;
                }

                // Skip unauthenticated users (scrapers, bots, random traffic)
                if ($entry->user === null) {
                    return false;
                }

                // Skip admin's own requests
                if ($entry->user?->role === User::ROLE_ADMIN) {
                    return false;
                }

                return true;
            }

            // Record exceptions, logs, mail always
            return true;
        });

        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];

            if ($entry->type === 'request') {
                $method = $entry->content['method'] ?? null;
                if ($method) $tags[] = strtoupper($method);

                $status = $entry->content['response_status'] ?? null;
                if ($status) $tags[] = $status;

                $uri = $entry->content['uri'] ?? '';
                if (str_contains($uri, '/login')) $tags[] = 'Login';
            }

            return $tags;
        });
    }

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token', 'password', 'password_confirmation']);
        Telescope::hideRequestHeaders(['cookie', 'x-csrf-token', 'x-xsrf-token']);
    }

    protected function authorization(): void
    {
        Telescope::auth(function (Request $request) {
            return $request->user() && $request->user()->role === User::ROLE_ADMIN;
        });
    }
}
