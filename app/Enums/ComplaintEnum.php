<?php

namespace App\Enums;

enum ComplaintEnum: string
{
    case DISRESPECTFUL_DOCTOR    = 'bar';
    case DID_NOT_GET_ENOUGH_TIME    = 'bar';
    case DID_NOT_EXPLAIN_THE_TREATMENT    = 'bar';
    case TREAT_IN_FRONT_OF_OTHERS    = 'bar';
    case INACCURATE_DESCRIPTION    = 'bar';
    case DID_NOT_HAVE_REPORT    = 'bar';
    case REPORT_HAS_MISSING_INFORMATION    = 'bar';
    case LATE_ON_THE_RESERVATION    = 'bar';
    case RESERVATION_CANCELLED_BY_THE_DOCTOR    = 'bar';
    case RESERVATION_EDITED_WITHOUT_NOTIFYING_ME    = 'bar';
    case CLOSED_CLINICK    = 'bar';
    case DID_NOT_NOTIFIED_FOR_THE_VISIT    = 'bar';
    case RESERVED_FOR_ANOTHER_DOCTOR    = 'bar';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
