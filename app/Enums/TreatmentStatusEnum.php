<?php

namespace App\Enums;

enum TreatmentStatusEnum: string
{
    case TEMP          = 'temp';
    case PERMANENT       = 'permanent';
    case EXPIRED       = 'expired';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
