<?php

namespace App\Enums;

enum VaccineVisitEnum: string
{
    case FIRST    = 'الأولى';
    case SECOND   = 'الثانية';
    case THIRD    = 'الثالثة';
    case FOURTH   = 'الرابعة';
    case FIFTH    = 'الخامسة';
    case SIXTH    = 'السادسة';
    case SEVENTH  = 'السابعة';
    case EIGHTH   = 'الثامنة';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
