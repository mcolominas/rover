<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlanetRequest;
use Illuminate\Http\JsonResponse;

class PlanetController extends JsonController
{
    public function store(StorePlanetRequest $request): JsonResponse
    {
        return $this->response('ok');
    }
}
