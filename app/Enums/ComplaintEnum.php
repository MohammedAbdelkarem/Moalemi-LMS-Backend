<?php

namespace App\Enums;

enum ComplaintEnum: string
{
    case DISRESPECTFUL_DOCTOR    = 'الطبيب استخدم أسلوبا غير معني أثناء الكشف';
    case DID_NOT_GET_ENOUGH_TIME    = 'الاستشارة كانت قصيرة وغير كافية لمناقشة مشكلتي';
    case DID_NOT_EXPLAIN_THE_TREATMENT    = 'غادرت الموعد دون فهم واضح لتشخيصي أو العلاج';
    case TREAT_IN_FRONT_OF_OTHERS    = 'لم يتم احترام خصوصيتي أثناء الكشف أو العلاج';
    case INACCURATE_DESCRIPTION    = 'أشعر أن التشخيص كان سطحيا أو لم يكن صحيحا';
    case DID_NOT_HAVE_REPORT    = 'لم أستلم التقرير الطبي بعد المعاينة';
    case REPORT_HAS_MISSING_INFORMATION    = 'التقرير يحتوي على معلومات ناقصة أو غير مكتملة';
    case LATE_ON_THE_RESERVATION    = 'وصلت في الوقت المحدد لكن الطبيب لم يكن متاح';
    case RESERVATION_CANCELLED_BY_THE_DOCTOR    = 'تم الغاء موعدي بدون تبرير واضح';
    case RESERVATION_EDITED_WITHOUT_NOTIFYING_ME    = 'تفاجأت بأن موعدي تم تعديله بدون اشعار مسبق';
    case CLOSED_CLINICK    = 'ذهبت في الموعد لكن العيادة كانت مغلقة';
    case DID_NOT_NOTIFIED_FOR_THE_VISIT    = 'لم يصلني اشعار تذكير بالموعد';
    case RESERVED_FOR_ANOTHER_DOCTOR    = 'تم تأكيد الموعد مع طبيب مختلف عما اخترته';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
