<?php

namespace App\Providers;

use App\Repositories\EmployeeRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Repositories\TeamRepository;
use App\Services\TeamService;
use Illuminate\Support\ServiceProvider;
use App\Models\Employee;
use App\Observers\EmployeeObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IEmployeeRepository::class, EmployeeRepository::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Employee::observe(EmployeeObserver::class);
    }
}
