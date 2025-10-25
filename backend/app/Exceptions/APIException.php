<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;

/**
 * Base exception class for errors.
 */
abstract class APIException extends \Exception
{
    public const int HTTP_STATUS_CODE = 422;

    abstract protected function getErrorCode(): int;

    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => $this->getErrorCode(),
            'message' => $this->getMessage(),
        ], static::HTTP_STATUS_CODE);
    }
}
