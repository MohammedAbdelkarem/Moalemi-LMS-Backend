<?php

namespace App\Enums;

enum AccessTypeEnum: string
{
    case FREE          = 'free';
    case PAID          = 'paid';
    case SUBSCRIPTION  = 'subscription';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
