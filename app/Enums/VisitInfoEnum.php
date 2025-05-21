<?php

namespace App\Enums;

enum VisitInfoEnum: string
{
    case TITLE          = 'title';
    case DESCRIPTION    = 'description';
    case NOTE           = 'note';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
