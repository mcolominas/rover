<?php

namespace App\Services;

use App\Contracts\PlanetServiceInterface;
use App\Contracts\ObstacleGeneratorInterface;
use App\Models\Planet;
use Illuminate\Support\Facades\DB;

class PlanetService implements PlanetServiceInterface
{
    protected ObstacleGeneratorInterface $obstacleGenerator;

    public function __construct(ObstacleGeneratorInterface $obstacleGenerator)
    {
        $this->obstacleGenerator = $obstacleGenerator;
    }

    /**
     * Create a new planet with the given dimensions and obstacle limits.
     *
     * @param int $width
     * @param int $height
     * @param int $minObstacles
     * @param int $maxObstacles
     * @return Planet
     */
    public function createPlanet(int $width, int $height, int $minObstacles, int $maxObstacles): Planet
    {
        return DB::transaction(function () use ($width, $height, $minObstacles, $maxObstacles) {
            $planet = Planet::create([
                'width' => $width,
                'height' => $height,
            ]);

            $this->obstacleGenerator->generate($planet, $minObstacles, $maxObstacles);

            return $planet;
        });
    }
}
