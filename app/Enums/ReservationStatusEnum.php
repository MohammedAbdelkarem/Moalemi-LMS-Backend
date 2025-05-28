<?php

namespace App\Enums;

enum ReservationStatusEnum: string
{
    case FOO    = 'bar';
    case PENDING    = 'pending';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
