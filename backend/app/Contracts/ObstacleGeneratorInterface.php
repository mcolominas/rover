<?php

namespace App\Contracts;

use App\Exceptions\ObstacleAttemptException;
use App\Exceptions\ObstacleLimitExceededException;
use App\Models\Planet;
use InvalidArgumentException;

interface ObstacleGeneratorInterface
{
    /**
     * Generate obstacles on the given planet within the specified limits.
     *
     * @param Planet $planet
     * @param int $min
     * @param int $max
     * @throws ObstacleLimitExceededException
     * @throws ObstacleAttemptException
     * @throws InvalidArgumentException
     * @return void
     */
    public function generate(Planet $planet, int $min, int $max): void;
}
