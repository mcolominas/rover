<?php

namespace App\Exceptions;

/**
 * Exception thrown when obstacle generation exceeds maximum attempts.
 */
class ObstacleAttemptException extends APIException
{
    public const int HTTP_STATUS_CODE = 409;

    public function __construct(int $attempts)
    {
        parent::__construct(
            "Too many attempts ({$attempts}) generating obstacles. Planet might be too small."
        );
    }

    protected function getErrorCode(): int
    {
        return 1003;
    }
}
