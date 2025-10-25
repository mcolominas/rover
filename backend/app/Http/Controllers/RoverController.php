<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecuteCommandsRequest;
use App\Http\Requests\LaunchRoverRequest;
use App\Models\Planet;
use App\Models\Rover;
use Illuminate\Http\JsonResponse;

class RoverController extends JsonController
{
    public function launch(LaunchRoverRequest $request, Planet $planet): JsonResponse{
        return $this->response('ok');
    }

    public function executeCommands(ExecuteCommandsRequest $request, Rover $rover): JsonResponse{
        return $this->response('ok');
    }
}
