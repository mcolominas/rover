<?php

namespace App\Services;

use App\Contracts\RoverServiceInterface;
use App\Models\Planet;
use App\Models\Rover;
use App\Enums\Direction;
use App\Enums\Movement;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InvalidPositionException;
use App\Exceptions\ObstacleDetectedException;
use App\Exceptions\RoverAlreadyExistsException;

class RoverService implements RoverServiceInterface
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
    public function launchRover(Planet $planet, int $x, int $y, Direction $direction): Rover
    {
        if (isset($planet->rover) || $planet->rover()->exists()) {
            throw new RoverAlreadyExistsException();
        }

        if ($x < 0 || $x >= $planet->width || $y < 0 || $y >= $planet->height) {
            throw new InvalidPositionException("Cannot launch rover outside planet boundaries.");
        }

        if ($planet->hasObstacle($x, $y)) {
            throw new InvalidPositionException("Cannot launch rover on an obstacle.");
        }

        return $planet->rover()->create([
            'x' => $x,
            'y' => $y,
            'direction' => $direction,
        ]);
    }

    /**
     * Execute a sequence of commands for the given rover.
     *
     * @param Rover $rover
     * @param string $commands
     * @return array
     * @throws InvalidPositionException
     * @throws ObstacleDetectedException
     */
    public function executeCommands(Rover $rover, string $commands): array
    {
        $planet = $rover->planet;

        return DB::transaction(function () use ($rover, $commands, $planet) {
            $path = [];

            foreach (str_split(strtoupper($commands)) as $cmd) {
                $movement = Movement::tryFrom($cmd)
                    ?? throw new InvalidPositionException("Invalid command: {$cmd}");

                match ($movement) {
                    Movement::LEFT  => $rover->direction = $rover->direction->turnLeft(),
                    Movement::RIGHT => $rover->direction = $rover->direction->turnRight(),
                    Movement::FORWARD => $this->moveForward($rover, $planet, $path),
                };

                if ($movement !== Movement::FORWARD) {
                    $this->moveForward($rover, $planet, $path);
                }

                $path[] = ['position' => ['x' => $rover->x, 'y' => $rover->y], 'direction' => $rover->direction->value, 'movement' => $movement->value];
            }

            $rover->save();

            return [
                'position' => ['x' => $rover->x, 'y' => $rover->y],
                'direction' => $rover->direction->value,
                'path' => $path
            ];
        });
    }

    /**
     * Move the rover forward in its current direction.
     *
     * @param Rover $rover
     * @param Planet $planet
     * @param array $path
     * @return void
     * @throws ObstacleDetectedException
     */
    protected function moveForward(Rover $rover, Planet $planet, array $path = []): void
    {
        [$newX, $newY] = $rover->direction->moveForward($rover->x, $rover->y, $planet->width, $planet->height);

        if ($planet->hasObstacle($newX, $newY)) {
            throw new ObstacleDetectedException($newX, $newY, $path);
        }

        $rover->x = $newX;
        $rover->y = $newY;
    }
}
