<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Helper to convert dates to the logged-in user's timezone
        Blade::directive('usertime', function ($expression) {
            return "<?php echo \\Carbon\\Carbon::parse($expression)->setTimezone(auth()->user()->timezone ?? 'UTC'); ?>";
        });
    }
}
