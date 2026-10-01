<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Pagination\Paginator::useBootstrapFour();

        if (env('APP_HTTPS', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Share settings with all views safely
        try {
            if (Schema::hasTable('settings')) {
                $globalSettings = [
                    'font_family' => Setting::get('font_family', 'Source Sans Pro'),
                    'font_size' => Setting::get('font_size', '16'),
                ];
                View::share('globalSettings', $globalSettings);
            } else {
                View::share('globalSettings', [
                    'font_family' => 'Source Sans Pro',
                    'font_size' => '16',
                ]);
            }
        } catch (\Exception $e) {
            // Fallback if DB is not reachable
            View::share('globalSettings', [
                'font_family' => 'Source Sans Pro',
                'font_size' => '16',
            ]);
        }
    }
}
