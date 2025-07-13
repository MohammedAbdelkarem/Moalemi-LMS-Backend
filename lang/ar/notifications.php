<?php

use App\Constants\NotificationMessages;
use App\Enums\Notifications\NotificationTypes;

return [

    /*
    |--------------------------------------------------------------------------
    | App notifications messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during App for various
    | notifications messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
    //Enum Keys
    NotificationTypes::AUTH->value           => "اشعارات المصادقة",
    NotificationTypes::RESERVATIONS->value   => "اشعارات الحجوزات",
    NotificationTypes::MEDICAL_PROFILE->value=> "اشعارات الملف الطبي",
    NotificationTypes::RATE->value           => "اشعارات التقييمات",
    NotificationTypes::COMPLAINTS->value     => "اشعارات الشكاوي",
    NotificationTypes::STEPS->value          => "اشعارات الخطوات",
    NotificationTypes::WATER->value          => "اشعارات المياه",
    NotificationTypes::SLEEP->value          => "اشعارات النوم",
    NotificationTypes::WEIGHT->value         => "اشعارات الوزن",
    NotificationTypes::GENERAL->value        => "اشعارات تحفيزية",
    NotificationTypes::ARTICLES->value       => "اشعارات المقالات",

    //
    "Suspend" => "حظر",
    "Unblock" => "Unblock",

    //Messages
    //Account
    NotificationMessages::LOGIN_TITLE   => "عملية تسجيل دخول جديدة",
    NotificationMessages::LOGIN_BODY    => "تمت عملية تسجيل دخول جديدة لحسابك من جهاز: :device من: :location",
    NotificationMessages::BAN_TITLE     => "لقد تم تقييد حسابك",
    NotificationMessages::BAN_BODY      => "حسابك محظور من استخدام بعض ميزات التطبيق حتى تاريخ: :bannedUntil بسبب: :reason",
    NotificationMessages::UNBAN_TITLE   => "لقد تم الغاء تقييد حسابك",
    NotificationMessages::UNBAN_BODY    => "بإمكانك الآن الاستفادة من جميع ميزات التطبيق ,لقد تم الغاء القيود على حسابك",

    //Customer Service Card
    NotificationMessages::CUSTOMER_SERVICE_CARD_DELETE_TITLE    => "لقد تم حذف بطاقة خدمة العملاء خاصتك",
    NotificationMessages::CUSTOMER_SERVICE_CARD_DELETE_BODY     => "تم حذف بطاقة خدمة العملاء خاصتك بالعنوان التالي: ':name'",
    NotificationMessages::CUSTOMER_SERVICE_CARD_CLOSE_TITLE     => "لقد تم إغلاق خدمة العملاء خاصتك",
    NotificationMessages::CUSTOMER_SERVICE_CARD_CLOSE_BODY      => "تم إغلاق بطاقة خدمة العملاء خاصتك بالعنوان التالي: ':name'",

    // Auth Messages
    NotificationMessages::REGISTER_TITLE       => "تسجيل حساب جديد",
    NotificationMessages::REGISTER_BODY        => "مرحًبا بك في تطبيقنا الطبي! تم إنشاء حسابك بنجاح 🎉",

    NotificationMessages::WELCOME_BACK_TITLE   => "تسجيل دخول",
    NotificationMessages::WELCOME_BACK_BODY    => "أهلاً بعودتك، :name! نتمنى لك يوماً صحياً 😊",

    NotificationMessages::DEVICE_LOGIN_TITLE   => "دخول من جهاز جديد",
    NotificationMessages::DEVICE_LOGIN_BODY    => "تم تسجيل دخول جديد إلى حسابك",

    //Reservations
    NotificationMessages::NO_SUBSCRIPTION_TITLE        => "لا يوجد اشتراك",
    NotificationMessages::NO_SUBSCRIPTION_BODY         => "لقد انتهى اشتراكك , قم بتجديد اشتراكك للحصول على خدمات التطبيق! 👨‍⚕️",
    
    //Reservations
    NotificationMessages::APPOINTMENT_BOOKED_TITLE        => "حجز موعد",
    NotificationMessages::APPOINTMENT_BOOKED_BODY         => "تم حجز موعدك مع د. :name يوم :date الساعة :time 🩺",

    NotificationMessages::DOCTOR_APPOINTMENT_BOOKED_TITLE        => "حجز موعد",
    NotificationMessages::DOCTOR_APPOINTMENT_BOOKED_BODY         => "لديك موعد جديد بانتظار الموافقة 👨‍⚕️",

    NotificationMessages::APPOINTMENT_CONFIRMED_TITLE     => "تأكيد موعد",
    NotificationMessages::APPOINTMENT_CONFIRMED_BODY      => "تم تأكيد موعدك مع د. :name يوم :date الساعة :time 🩺",

    NotificationMessages::DOCTOR_APPOINTMENT_CONFIRMED_TITLE     => "تأكيد موعد",
    NotificationMessages::DOCTOR_APPOINTMENT_CONFIRMED_BODY      =>"تم تأكيد الموعد مع المريض :name يوم :date الساعة :time 🩺",


    //ReservationDailyReminderCommand
    NotificationMessages::APPOINTMENT_REMINDER_24_TITLE   => "تذكير قبل 24 ساعة",
    NotificationMessages::APPOINTMENT_REMINDER_24_BODY    => "موعدك بعد أقل من 24 ساعة مع د. :name. نتمنى لك زيارة مريحة 💚",

    //ReservationHourlyReminderCommand
    NotificationMessages::APPOINTMENT_REMINDER_1_TITLE    => "تذكير قبل ساعة",
    NotificationMessages::APPOINTMENT_REMINDER_1_BODY     => "تبقى ساعة واحدة على موعدك مع د. :name. استعد! ⏰",

    NotificationMessages::APPOINTMENT_CANCELLED_TITLE     => "إلغاء الموعد",
    NotificationMessages::APPOINTMENT_CANCELLED_BODY      => "تم إلغاء موعدك مع د. :name. يمكنك حجز موعد جديد بسهولة.",

    NotificationMessages::DOCTOR_APPOINTMENT_CANCELLED_TITLE     => "إلغاء الموعد",
    NotificationMessages::DOCTOR_APPOINTMENT_CANCELLED_BODY      => "قام المريض :name بإلغاء موعده",

    NotificationMessages::APPOINTMENT_REJECTED_TITLE      => "رفض الموعد من قبل الطبيب",
    NotificationMessages::APPOINTMENT_REJECTED_BODY       => "تم رفض موعدك مع د. :name. والسبب هو :reason يمكنك حجز موعد جديد في وقت آخر.",

    NotificationMessages::DOCTOR_APPOINTMENT_REJECTED_TITLE      => "رفض الموعد من قبل الطبيب",
    NotificationMessages::DOCTOR_APPOINTMENT_REJECTED_BODY       => "تم رفض الموعد للمريض :name والسبب هو :reason",

    NotificationMessages::APPOINTMENT_DID_NOT_COME_TITLE      => "لم تحضر الموعد",
    NotificationMessages::APPOINTMENT_DID_NOT_COME_BODY       => "تم اغلاق موعدك مع الطبيب :name لأنك لم تحضر",

    NotificationMessages::DOCTOR_APPOINTMENT_DID_NOT_COME_TITLE      => "لم يحضر الموعد",
    NotificationMessages::DOCTOR_APPOINTMENT_DID_NOT_COME_BODY       =>"تم اغلاق الموعد للمريض :name لأنه لم يحضر",

    NotificationMessages::APPOINTMENT_ADMIN_CANCEL_TITLE  => "إلغاء الموعد من قبل الإدارة",
    NotificationMessages::APPOINTMENT_ADMIN_CANCEL_BODY   => "تم إلغاء موعدك مع د. :name. بسبب :reason يمكنك حجز موعد جديد.",

    NotificationMessages::DOCTOR_APPOINTMENT_ADMIN_CANCEL_TITLE  => "إلغاء الموعد من قبل الإدارة",
    NotificationMessages::DOCTOR_APPOINTMENT_ADMIN_CANCEL_BODY   => "تم إلغاء الموعد من قبل الإدارة بسبب :reason",

    // Medical Report
    NotificationMessages::MEDICAL_REPORT_TITLE => "تقرير طبي جديد",
    NotificationMessages::MEDICAL_REPORT_BODY  => "تقرير جديد من د. :name أصبح متاحًا في ملفك 📃",

    // Treatments
    NotificationMessages::NEW_PRESCRIPTION_TITLE      => "وصفة جديدة",
    NotificationMessages::NEW_PRESCRIPTION_BODY       => "💊 وصفة طبية جديدة من د. :name بانتظارك داخل التطبيق.",

    NotificationMessages::NEW_RECOMMENDATION_TITLE    => "توصية جديدة",
    NotificationMessages::NEW_RECOMMENDATION_BODY     => "💊  توصية جديدة من د. :name بانتظارك داخل التطبيق.",

    //MedicineHourlyReminderCommand
    // NotificationMessages::MEDICATION_REMINDER_TITLE   => "وقت تناول الدواء",
    // NotificationMessages::MEDICATION_REMINDER_BODY    => "💊 تذكير: حان وقت تناول دواء :medication. صحتك أولويتنا!",

    //MedicineForgetReminderCommand
    // NotificationMessages::MISSED_DOSE_TITLE           => "نسيان جرعة",
    // NotificationMessages::MISSED_DOSE_BODY            => "هل نسيت تناول دواء :medication؟ لا بأس – حاول الالتزام أكثر 💙",

    // Complaints & Ratings
    NotificationMessages::COMPLAINT_SUBMITTED_TITLE => "تقديم شكوى",
    NotificationMessages::COMPLAINT_SUBMITTED_BODY  => "تم استلام شكواك – سنتابعها بأسرع وقت. نشكرك على ملاحظاتك 🙏",

    NotificationMessages::COMPLAINT_UPDATED_TITLE   => "تحديث شكوى",
    NotificationMessages::COMPLAINT_UPDATED_BODY    => "📩 تم تحديث حالة الشكوى الخاصة بك. راجع التفاصيل داخل التطبيق.",

    //ReservationRateReminderCommand
    NotificationMessages::APPOINTMENT_RATING_TITLE  => "طلب تقييم بعد ٢٤ ساعة من الموعد",
    NotificationMessages::APPOINTMENT_RATING_BODY   => "كيف كانت زيارتك مع د. :name؟ شاركنا رأيك ✨",

    // Activity & Steps

    //StepsReminderCommand //6 pm
    // NotificationMessages::INACTIVITY_TITLE         => "عدم تقدم",
    // NotificationMessages::INACTIVITY_BODY          => "خطواتك تصنع فرقًا صغيرًا كل يوم! ما رأيك بنزهة قصيرة الآن؟ 🚶‍♂️",

    NotificationMessages::DAILY_PROGRESS_TITLE     => "تقدم يومي",
    NotificationMessages::DAILY_PROGRESS_BODY      => "👏 لقد مشيت :steps خطوة اليوم! استمر في التقدم 💪",

    NotificationMessages::STEP_GOAL_ACHIEVED_TITLE => "تحقق الهدف",
    NotificationMessages::STEP_GOAL_ACHIEVED_BODY  => "رائع! وصلت إلى :goal خطوة اليوم. تمت إضافة :points دولار لرصيدك! 🎯",


    // NotificationMessages::STEP_WITHDRAWAL_TITLE    => "سحب رصيد الخطوات",
    // NotificationMessages::STEP_WITHDRAWAL_BODY     => "تم سحب مبلغ من رصيدك بقيمة :withdrawal. تبقى في رصيدك :remaining.",

    // WaterReminderCommand // 9 to 9 , every hour
    // NotificationMessages::WATER_REMINDER_TITLE     => "تذكير بالشرب",
    // NotificationMessages::WATER_REMINDER_BODY      => "هل شربت كوب ماء اليوم؟ ابقَ منتعشًا 💧",

    NotificationMessages::WATER_GOAL_ACHIEVED_TITLE=> "تحقق الهدف",
    NotificationMessages::WATER_GOAL_ACHIEVED_BODY => "أحسنت! وصلت إلى هدفك اليومي من شرب الماء 💦",

    // SleepReminderCommand     // 9 pm
    // NotificationMessages::SLEEP_REMINDER_TITLE     => "تذكير بالنوم",
    // NotificationMessages::SLEEP_REMINDER_BODY      => "اقترب وقت النوم. خذ قسطًا من الراحة 💤",

    // SleepReportCommand       // 9 am
    // NotificationMessages::SLEEP_REVIEW_TITLE       => "مراجعة نوم",
    // NotificationMessages::SLEEP_REVIEW_BODY        => "نمت :hours ساعة الليلة الماضية. تابع هذا النمط الجيد! 🌙",

    // Weight

    //UpdateMedicalInfoReminderCommand // every month
    // NotificationMessages::WEIGHT_UPDATE_REQUEST_TITLE => "طلب تحديث الوزن",
    // NotificationMessages::WEIGHT_UPDATE_REQUEST_BODY  => "📊 حدّث بيانات وزنك وطولك لتحصل على تحليلات أدق",

    // MotivationalCommand , calc bmi once a week and then send for all the users , checking the managment
    // NotificationMessages::PERFORMANCE_IMPROVED_TITLE  => "تحسّن في الأداء",
    // NotificationMessages::PERFORMANCE_IMPROVED_BODY   => "💪 بنية جسمك في تحسّن! استمر على هذا الأداء الرائع.",

    // Wellness & Articles

    //MorningCommand 10 am
    // NotificationMessages::GOOD_MORNING_TITLE           => "صباح الخير",
    // NotificationMessages::GOOD_MORNING_BODY            => "☀ صباح الصحة! تذّكر: كل خطوة نحو العافية تهم.",

    //EveningCommand , 7 pm
    // NotificationMessages::GOOD_EVENING_TITLE           => "مساء الخير",
    // NotificationMessages::GOOD_EVENING_BODY            => "🌙 ختام اليوم بلحظة راحة. لا تنسَ الاهتمام بنفسك",

    //HealthCommand , once a week at 5 pm
    // NotificationMessages::HEALTHCARE_REMINDER_TITLE    => "هل تهتم بصحتك؟",
    // NotificationMessages::HEALTHCARE_REMINDER_BODY     => "💚 صحتك أهم استثمار! استمر في العناية بنفسك.",

    //HealthTipCommand
    // NotificationMessages::HEALTH_TIP_TITLE             => "معلومة صحية",
    // NotificationMessages::HEALTH_TIP_BODY              => "💡 هل تعلم؟ شرب الماء بانتظام يساعد على تحسين التركيز والوظائف الحيوية.",

    //when creating
    NotificationMessages::NEW_ARTICLE_TITLE            => "مقال جديد",
    NotificationMessages::NEW_ARTICLE_BODY             => "📰 مقال جديد بانتظارك: :title. اكتشف ما يمكن أن يُفيد صحتك اليوم!",

    // when creating , did not related to the management
    NotificationMessages::DOCTOR_ARTICLE_TITLE         => "مقال جديد من طبيبك المفضل",
    NotificationMessages::DOCTOR_ARTICLE_BODY          => "🩺 مقال جديد من د. :doctor بعنوان :title. اكتشف ما يمكن أن يُفيد صحتك اليوم!",

    NotificationMessages::ARTICLE_COMMENT_REPLY_TITLE  => "رد على تعليقك",
    NotificationMessages::ARTICLE_COMMENT_REPLY_BODY   => "قام الطبيب :name بالرد على تعليقك على مقال !",

];
