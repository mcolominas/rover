<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class APIController extends Controller
{
    /**
     * Send a JSON response.
     *
     * @param string $message
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function response(string $message, $data = null, int $status = 200): JsonResponse
    {
        $response = ['message' => $message];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }
}
