<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    private const SCRAPER_AGENTS = [
        'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
        'yandex', 'amazonbot', 'semrushbot', 'googleother', 'ahrefsbot',
        'adsbot', 'applebot', 'dotbot', 'bot', 'crawler', 'curl',
        'facebookexternalhit', 'bytespider', 'bytedance', 'tiktok',
    ];

    private const EXCLUDE_PATHS = [
        '.well-known', '.txt', '.xml', '.json', '.aspx', '.ini',
        '/wp-json', '/wp-admin', '/wp-content', '/wp-includes', '/cgi-bin',
    ];

    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            // Skip slow query logging unless > 5ms
            if ($entry->type === 'query') {
                if (!isset($entry->content['time']) || (float) $entry->content['time'] < 5) {
                    return false;
                }
            }

            // Skip requests from unauthenticated users entirely
            if ($entry->type === 'request') {
                if ($entry->user === null) {
                    return false;
                }

                // Skip admin's own requests (noise)
                if ($entry->user?->role === User::ROLE_ADMIN) {
                    return false;
                }

                $uri = $entry->content['uri'] ?? '';
                $userAgent = strtolower($entry->content['headers']['user-agent'] ?? '');
                $acceptHeader = $entry->content['headers']['accept'] ?? '';

                // Skip scrapers/bots
                if ($acceptHeader === '*/*') {
                    return false;
                }

                $pattern = '/' . implode('|', array_map('preg_quote', self::SCRAPER_AGENTS)) . '/i';
                if (preg_match($pattern, $userAgent)) {
                    return false;
                }

                // Skip junk paths
                foreach (self::EXCLUDE_PATHS as $excludePath) {
                    if (str_contains($uri, $excludePath)) {
                        return false;
                    }
                }

                if (str_starts_with($uri, '//')) {
                    return false;
                }
            }

            return true;
        });

        // Tag requests with HTTP method and status
        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];

            if ($entry->type === 'request') {
                $method = $entry->content['method'] ?? null;
                if ($method) $tags[] = strtoupper($method);

                $status = $entry->content['response_status'] ?? null;
                if ($status) $tags[] = $status;
            }

            return $tags;
        });
    }

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);
        Telescope::hideRequestHeaders(['cookie', 'x-csrf-token', 'x-xsrf-token']);
    }

    protected function authorization(): void
    {
        Telescope::auth(function (Request $request) {
            return $request->user() && $request->user()->role === User::ROLE_ADMIN;
        });
    }
}
