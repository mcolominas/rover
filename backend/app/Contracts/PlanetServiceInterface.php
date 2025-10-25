<?php

namespace App\Contracts;

use App\Exceptions\ObstacleAttemptException;
use App\Exceptions\ObstacleLimitExceededException;
use App\Models\Planet;

interface PlanetServiceInterface
{
    /**
     * Create a new planet with the given dimensions and obstacle limits.
     *
     * @param int $width
     * @param int $height
     * @param int $minObstacles
     * @param int $maxObstacles
     * @return Planet
     * @throws ObstacleLimitExceededException
     * @throws ObstacleAttemptException
     */
    public function createPlanet(int $width, int $height, int $minObstacles, int $maxObstacles): Planet;
}
