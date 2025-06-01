<?php

namespace App\Enums;

enum SmokeEnum: string
{
    case SMOKER    = 'smoker';
    case NON_SMOKER    = 'non_smoker';
    case PREV_SMOKER    = 'prev_smoker';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
