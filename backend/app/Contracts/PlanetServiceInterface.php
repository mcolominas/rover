<?php

namespace App\Contracts;

use App\Models\Planet;

interface PlanetServiceInterface
{
    public function createPlanet(int $width, int $height, int $minObstacles, int $maxObstacles): Planet;
}
