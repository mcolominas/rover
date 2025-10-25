<?php

namespace App\Contracts;

use App\Models\Planet;
use App\Models\Rover;
use App\Enums\Direction;
use App\Exceptions\InvalidPositionException;
use App\Exceptions\ObstacleDetectedException;
use App\Exceptions\RoverAlreadyExistsException;

interface RoverServiceInterface
{
    /**
     * Launch a rover on the specified planet at given coordinates and direction.
     *
     * @param Planet $planet
     * @param int $x
     * @param int $y
     * @param Direction $direction
     * @return Rover
     * @throws RoverAlreadyExistsException
     * @throws InvalidPositionException
     */
    public function launchRover(Planet $planet, int $x, int $y, Direction $direction): Rover;


    /**
     * Execute a sequence of commands for the given rover.
     *
     * @param Rover $rover
     * @param string $commands
     * @return array
     * @throws InvalidPositionException
     * @throws ObstacleDetectedException
     */
    public function executeCommands(Rover $rover, string $commands): array;
}
