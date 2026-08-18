<?php

namespace App\Providers;

use App\Models\StableBranding;
use App\Services\StripeService;
use App\Services\StripeServiceInterface;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(StripeServiceInterface::class, StripeService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!$view->offsetExists('stableBranding')) {
                $view->with('stableBranding', StableBranding::first());
            }
        });
    }
}
