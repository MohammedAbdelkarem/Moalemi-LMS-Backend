<?php

namespace App\Enums;

enum SystemSettingsKeys: string
{
    //TODO:TEMPLATE
    case SYP_TO_DLR         = 'SP To USD';
    case BANNER_LIVE_TIME   = 'Banner live time';
    case REEL_LIVE_TIME     = 'Reel live time';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}