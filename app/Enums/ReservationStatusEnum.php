<?php

namespace App\Enums;

enum ReservationStatusEnum: string
{
    case PENDING            = 'pending';
    case ACCEPTED           = 'accepted';
    case REJECTED           = 'rejected';
    case CANCELLED          = 'cancelled';
    case DONE               = 'done';
    case DID_NOT_COME       = 'did_not_come';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
