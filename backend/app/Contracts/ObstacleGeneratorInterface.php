<?php

namespace App\Contracts;

use App\Models\Planet;

interface ObstacleGeneratorInterface
{
    public function generate(Planet $planet, int $min, int $max): void;
}
