<?php

namespace App\Exceptions;

/**
 * Exception thrown when a rover already exists on the planet.
 */
class RoverAlreadyExistsException extends APIException
{
    public function __construct(string $message = "A rover already exists on this planet.")
    {
        parent::__construct($message);
    }

    protected function getErrorCode(): int
    {
        return 1005;
    }
}
