<?php

namespace App\Exceptions;

/**
 * Exception thrown when a rover is given an invalid position.
 */
class InvalidPositionException extends APIException
{
    public function __construct(string $message = "Invalid position for rover.")
    {
        parent::__construct($message);
    }

    protected function getErrorCode(): int
    {
        return 1001;
    }
}
