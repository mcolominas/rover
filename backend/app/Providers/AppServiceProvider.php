<?php

namespace App\Providers;

use App\Contracts\ObstacleGeneratorInterface;
use App\Contracts\PlanetServiceInterface;
use App\Contracts\RoverServiceInterface;
use App\Services\ObstacleGenerator;
use App\Services\PlanetService;
use App\Services\RoverService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ObstacleGeneratorInterface::class, ObstacleGenerator::class);
        $this->app->bind(PlanetServiceInterface::class, PlanetService::class);
        $this->app->bind(RoverServiceInterface::class, RoverService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
