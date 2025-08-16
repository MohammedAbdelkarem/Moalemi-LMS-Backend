<?php

namespace App\Enums;

enum QuestionTypeEnum: string
{
    case ONE_SELECT      = 'one_select';
    case MULTIPLE_SELECT = 'multiple_select';
    case TRUE_FALSE      = 'true_false';
    case TEXT            = 'text';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
