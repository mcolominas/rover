<?php

namespace App\Http\Controllers;

use App\Contracts\RoverServiceInterface;
use App\Enums\Direction;
use App\Exceptions\RoverAlreadyExistsException;
use App\Exceptions\InvalidPositionException;
use App\Exceptions\ObstacleDetectedException;
use App\Http\Requests\ExecuteCommandsRequest;
use App\Http\Requests\LaunchRoverRequest;
use App\Http\Resources\CommandResource;
use App\Http\Resources\RoverResource;
use App\Models\Planet;
use App\Models\Rover;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use ValueError;
use TypeError;


class RoverController extends APIController
{
    public function __construct(protected RoverServiceInterface $roverService) {}

    /**
     * Launch a new rover on a planet.
     *
     * @param LaunchRoverRequest $request
     * @param Planet $planet
     * @return JsonResponse
     * @throws ValidationException
     * @throws InvalidArgumentException
     * @throws ValueError
     * @throws TypeError
     * @throws RoverAlreadyExistsException
     * @throws InvalidPositionException
     * @throws BindingResolutionException
     */
    public function launch(LaunchRoverRequest $request): JsonResponse
    {
        $data = $request->validated();

        $rover = $this->roverService->launchRover(
            planet: $request->getPlanet(),
            x: $data['x'],
            y: $data['y'],
            direction: Direction::from($data['direction'])
        );

        $roverResource = new RoverResource($rover);

        return $this->response('Rover launched successfully.', $roverResource, 201);
    }

    /**
     * Execute commands to rover
     *
     * @param ExecuteCommandsRequest $request
     * @param Rover $rover
     * @return JsonResponse
     * @throws ValidationException
     * @throws InvalidArgumentException
     * @throws InvalidPositionException
     * @throws ObstacleDetectedException
     * @throws BindingResolutionException
     */
    public function executeCommands(ExecuteCommandsRequest $request, Rover $rover): JsonResponse
    {
        $commands = $request->validated()['commands'];

        $result = $this->roverService->executeCommands($rover, $commands);
        $resultResource = new CommandResource($result);

        return $this->response('Commands executed successfully.', $resultResource);
    }
}
