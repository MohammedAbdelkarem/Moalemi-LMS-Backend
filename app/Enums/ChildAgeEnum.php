<?php

namespace App\Enums;

enum ChildAgeEnum: string
{
    case AT_BIRTH             = 'منذ الولادة';
    case THREE_MONTHS         = 'بداية الشهر الثالث';
    case FIVE_MONTHS          = 'بداية الشهر الخامس';
    case SEVEN_MONTHS         = 'بداية الشهر السابع';
    case ONE_YEAR             = 'بعمر السنة';
    case ONE_AND_HALF_YEAR    = 'بعمر السنة والنصف';
    case FIRST_GRADE          = 'الصف الأول';
    case SIXTH_GRADE          = 'الصف السادس';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
