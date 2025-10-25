<?php

namespace App\Enums;

enum Direction: string
{
    case NORTH = 'N';
    case EAST  = 'E';
    case SOUTH = 'S';
    case WEST  = 'W';

    /**
     * Turn the rover left from its current direction.
     *
     * @return Direction
     */
    public function turnLeft(): Direction
    {
        return match ($this) {
            self::NORTH => self::WEST,
            self::WEST  => self::SOUTH,
            self::SOUTH => self::EAST,
            self::EAST  => self::NORTH,
        };
    }

    /**
     * Turn the rover right from its current direction.
     *
     * @return Direction
     */
    public function turnRight(): Direction
    {
        return match ($this) {
            self::NORTH => self::EAST,
            self::EAST  => self::SOUTH,
            self::SOUTH => self::WEST,
            self::WEST  => self::NORTH,
        };
    }

    /**
     * Move the rover forward in its current direction.
     *
     * @param int $x
     * @param int $y
     * @param int $widthPlanet
     * @param int $heightPlanet
     * @return array
     */
    public function moveForward(int $x, int $y, int $widthPlanet, int $heightPlanet): array
    {
        return match($this) {
            self::NORTH => [$x, ($y + 1) % $heightPlanet],
            self::SOUTH => [$x, ($y - 1 + $heightPlanet) % $heightPlanet],
            self::EAST  => [($x + 1) % $widthPlanet, $y],
            self::WEST  => [($x - 1 + $widthPlanet) % $widthPlanet, $y],
        };
    }
}
