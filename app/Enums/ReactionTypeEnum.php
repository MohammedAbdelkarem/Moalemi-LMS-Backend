<?php

namespace App\Enums;

enum ReactionTypeEnum: string
{
    case LIKE    = 'like';
    case COMMENT      = 'comment';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
