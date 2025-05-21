<?php

namespace App\Enums\Notifications;

enum NotificationTypes: string
{
    case PUBLIC = 'public';
    case PRODUCT = 'product';
    case ACCOUNT = 'account';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}