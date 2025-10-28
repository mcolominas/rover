<?php

namespace App\Services;

use App\Contracts\ObstacleGeneratorInterface;
use App\Models\Planet;
use App\Exceptions\ObstacleAttemptException;
use App\Exceptions\ObstacleLimitExceededException;
use InvalidArgumentException;

class ObstacleGenerator implements ObstacleGeneratorInterface
{

    /**
     * Generate obstacles for the given planet.
     *
     * @param Planet $planet
     * @param int $min
     * @param int $max
     * @return void
     * @throws ObstacleLimitExceededException
     * @throws ObstacleLimitExceededException
     * @throws InvalidArgumentException
     */
    public function generate(Planet $planet, int $min, int $max): void
    {
        $totalCells = $planet->width * $planet->height;

        if ($max >= $totalCells) {
            throw new ObstacleLimitExceededException($max, $totalCells);
        }

        if($min > $max){
            throw new InvalidArgumentException('The minimum value cannot be greater than the maximum value.');
        }

        $obstaclesNumber = rand($min, $max);
        $obstacles = [];
        $attempts = 0;
        $maxAttempts = $obstaclesNumber * 5;

        while (count($obstacles) < $obstaclesNumber) {
            if ($attempts++ > $maxAttempts) {
                throw new ObstacleAttemptException($attempts);
            }

            $x = rand(0, $planet->width - 1);
            $y = rand(0, $planet->height - 1);

            if (isset($obstacles["$x,$y"])) continue;

            $obstacles["$x,$y"] = ['x' => $x, 'y' => $y];
        }

        $planet->obstacles()->createMany(array_values($obstacles));
    }
}
