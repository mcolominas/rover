<?php

namespace App\Exceptions;

/**
 * Exception thrown when the requested number of obstacles exceeds available cells.
 */
class ObstacleLimitExceededException extends APIException
{
    public const int HTTP_STATUS_CODE = 409;

    public function __construct(int $max, int $totalCells)
    {
        parent::__construct(
            "Cannot generate more obstacles ({$max}) than available cells ({$totalCells})."
        );
    }
    protected function getErrorCode(): int
    {
        return 1004;
    }
}
