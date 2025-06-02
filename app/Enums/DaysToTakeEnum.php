<?php

namespace App\Enums;

enum DaysToTakeEnum: string
{
    case EVERY_DAY  = 'every_day';
    case WHEN_NEEDED = 'when_needed';
    case CUSTOM      = 'custom';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
