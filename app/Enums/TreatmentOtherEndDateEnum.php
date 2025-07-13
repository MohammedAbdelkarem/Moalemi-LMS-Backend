<?php

namespace App\Enums;

enum TreatmentOtherEndDateEnum: string
{
    case WHEN_GETTING_BETTER    = 'when_getting_better';
    case DONT_KNOW              = 'dont_know';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
