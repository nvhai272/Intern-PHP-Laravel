<?php

namespace App\Providers;

use App\Repositories\TeamRepository;
use App\Services\TeamService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TeamRepository::class, function ($app) {
            return new TeamRepository(new \App\Models\Team());
        });

        $this->app->bind(TeamService::class, function ($app) {
            return new TeamService($app->make(TeamRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
