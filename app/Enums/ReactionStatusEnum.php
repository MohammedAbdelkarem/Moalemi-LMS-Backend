<?php

namespace App\Enums;

enum ReactionStatusEnum: string
{
    case EXIST    = 'exist';
    case DELETED      = 'deleted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
