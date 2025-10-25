<?php

namespace App\Providers;

use App\Contracts\ObstacleGeneratorInterface;
use App\Services\ObstacleGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ObstacleGeneratorInterface::class, ObstacleGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
