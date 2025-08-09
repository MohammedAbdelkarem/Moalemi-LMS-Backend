<?php

namespace App\Enums;

enum VaccineEnum: string
{
    case VISIT_1 = 'السل + كبد طفلي1 + شلل فموي صفر.';
    case VISIT_2 = 'اللقاح الحاوي على الرباعي1+ شلل عضلي1+ كبد طفلي2';
    case VISIT_3 = 'اللقاح الحاوي على الرباعي2+ شلل عضلي2';
    case VISIT_4 = 'اللقاح الحاوي على الرباعي3+ كبد طفلي 3 + شلل فموي1';
    case VISIT_5 = 'لقاح MMR1 + شلل فموي 2 + فيتامين A عيار 200000وحدة';
    case VISIT_6 = 'اللقاح الحاوي على الرباعي الداعم + شلل فموي داعم + لقاح MMR2 + فيتامين A عيار 200000وحدة';
    case VISIT_7 = 'ثنائي طفلي + شلل فموي';
    case VISIT_8 = 'ثنائي كهلي';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
