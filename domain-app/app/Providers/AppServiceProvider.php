<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Services\DomainCheckerService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DomainCheckerService::class, fn(): DomainCheckerService => new DomainCheckerService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
