<?php

namespace App\Enums;

enum TreatmentTypeEnum: string
{
    case MEDICINE          = 'medicine';
    case INSTRUCTION       = 'instruction';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
