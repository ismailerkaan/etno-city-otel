<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view): void {
            if (! isset($view->getData()['siteSetting'])) {
                try {
                    if (Schema::hasTable('site_settings')) {
                        $view->with('siteSetting', SiteSetting::query()->first());
                    }
                } catch (\Throwable) {
                    // Database may not be connected or table may not exist yet
                }
            }
        });
    }
}
