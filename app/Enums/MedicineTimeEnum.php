<?php

namespace App\Enums;

enum MedicineTimeEnum: string
{
    case FOO    = 'bar';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
