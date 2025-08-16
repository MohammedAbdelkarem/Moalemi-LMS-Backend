<?php

namespace App\Enums;

enum QuizResultEnum: string
{
    case SUCCESS = 'success';
    case FAIL    = 'fail';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
