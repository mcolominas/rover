<?php

namespace App\Enums;

enum Movement: string
{
    case FORWARD = 'F';
    case LEFT = 'L';
    case RIGHT = 'R';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
