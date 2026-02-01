<?php

namespace App\Enums\Notifications;

enum NotificationTypes: string
{
    case AUTH = 'auth';
    case MOAALEMI = 'moaalemi';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}