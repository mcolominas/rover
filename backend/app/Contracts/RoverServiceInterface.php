<?php

namespace App\Contracts;

use App\Models\Planet;
use App\Models\Rover;
use App\Enums\Direction;

interface RoverServiceInterface
{
    public function launchRover(Planet $planet, int $x, int $y, Direction $direction): Rover;

    public function executeCommands(Rover $rover, string $commands): array;
}
