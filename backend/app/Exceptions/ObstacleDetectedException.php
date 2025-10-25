<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

/**
 * Exception thrown when a rover detects an obstacle in its path.
 */
class ObstacleDetectedException extends APIException
{
    protected int $x;
    protected int $y;
    protected array $path;

    public function __construct(int $x, int $y, array $path = [])
    {
        parent::__construct("Obstacle detected at position ({$x}, {$y}).");
        $this->x = $x;
        $this->y = $y;
        $this->path = $path;
    }

    protected function getErrorCode(): int {
        return 1002;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function getCoordinates(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }

    public function getX(): int
    {
        return $this->x;
    }
    public function getY(): int
    {
        return $this->y;
    }
    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => $this->getErrorCode(),
            'message' => $this->getMessage(),
            'coordinates' => $this->getCoordinates(),
            'path' => $this->getPath(),
        ], 422);
    }
}
