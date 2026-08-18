<?php

namespace App\Providers;

use App\Services\MailConfigProvider;
use App\Services\StripeConfigProvider;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
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
     *
     * Applies SMTP and Stripe settings from the database to Laravel's config.
     * Skips when running in console (artisan commands) except during tests
     * and queue workers, so queued emails use DB settings.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole() && !$this->app->runningUnitTests() && !$this->isQueueWorker()) {
            return;
        }

        $this->app->make(MailConfigProvider::class)->apply();
        $this->app->make(StripeConfigProvider::class)->apply();
    }

    /**
     * Determine if the application is running as a queue worker.
     */
    protected function isQueueWorker(): bool
    {
        return str_contains($_SERVER['argv'][1] ?? '', 'queue:work')
            || str_contains($_SERVER['argv'][1] ?? '', 'queue:listen');
    }
}
