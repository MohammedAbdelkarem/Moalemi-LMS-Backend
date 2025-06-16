<?php

namespace App\Enums;

enum ComplaintEnum: string
{
    case DISRESPECTFUL_DOCTOR    = 'bar';
    case DID_NOT_GET_ENOUGH_TIME    = 'b2ar';
    case DID_NOT_EXPLAIN_THE_TREATMENT    = 'b3ar';
    case TREAT_IN_FRONT_OF_OTHERS    = 'b4ar';
    case INACCURATE_DESCRIPTION    = 'b5ar';
    case DID_NOT_HAVE_REPORT    = 'b6ar';
    case REPORT_HAS_MISSING_INFORMATION    = 'b7ar';
    case LATE_ON_THE_RESERVATION    = 'b8ar';
    case RESERVATION_CANCELLED_BY_THE_DOCTOR    = 'b9ar';
    case RESERVATION_EDITED_WITHOUT_NOTIFYING_ME    = 'b11ar';
    case CLOSED_CLINICK    = 'b22ar';
    case DID_NOT_NOTIFIED_FOR_THE_VISIT    = 'b33ar';
    case RESERVED_FOR_ANOTHER_DOCTOR    = 'b44ar';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
