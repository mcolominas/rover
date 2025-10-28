<?php

namespace App\Http\Controllers;

use App\Contracts\PlanetServiceInterface;
use App\Exceptions\ObstacleAttemptException;
use App\Exceptions\ObstacleLimitExceededException;
use App\Http\Requests\StorePlanetRequest;
use App\Http\Resources\PlanetResource;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;

class PlanetController extends APIController
{
    public function __construct(protected PlanetServiceInterface $planetService) {}


    /**
     * Create a new planet.
     *
     * @param StorePlanetRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws ObstacleLimitExceededException
     * @throws ObstacleAttemptException
     */
    public function store(StorePlanetRequest $request): JsonResponse
    {
        $width = config('planet.width');
        $height = config('planet.height');
        $minObstacles = config('planet.min_obstacles');
        $maxObstacles = config('planet.max_obstacles');

        $planet = $this->planetService->createPlanet($width, $height, $minObstacles, $maxObstacles);
        $planetResource = new PlanetResource($planet);

        return $this->response('Planet created successfully.', $planetResource, 201);
    }
}
