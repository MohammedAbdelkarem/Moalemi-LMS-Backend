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
    NotificationTypes::MOAALEMI->value       => "اشعارات معلمي",
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

    // Education Messages
    NotificationMessages::NEW_LESSON_TITLE         => "درس جديد في {{subject_name}} جاهز لك 📘",
    NotificationMessages::NEW_LESSON_BODY          => "نزل الدرس \"{{lesson_title}}\" ضمن منهاج \"{{subject_name}}\". ادخل شوف الشرح و تابع تقدمك خطوة بخطوة.",

    // NotificationMessages::INCOMPLETE_LESSON_TITLE  => "خلصت الدرس المتبقي من 🎯 \"{{lesson_title}}\"",
    // NotificationMessages::INCOMPLETE_LESSON_BODY   => "انت فتحت متأخر و متبقي الدرس ✔️. تابع اليوم حتى تكمله و ما يتراكم عليك شيء.",

    NotificationMessages::COMPLETE_LESSON_TITLE    => "أحسنت يا {{student_name}} 👏 أنهيت درس \"{{lesson_title}}\"",
    NotificationMessages::COMPLETE_LESSON_BODY     => "أنهيت درس \"{{lesson_title}}\"، الدرس التالي المقترح: \"{{next_lesson}}\". خطوة بخطوة نحو التميز.",

    NotificationMessages::NEW_QUIZ_TITLE           => "اختبار جديد في 📝 {{subject_name}}",
    NotificationMessages::NEW_QUIZ_BODY            => "تم إضافة اختبار \"{{quiz_title}}\" في مادة \"{{subject_name}}\". آخر موعد للتسليم: {{deadline}}. بلش اليوم و ربح بالك.",

    NotificationMessages::LOW_ACTIVITY_TITLE       => "خطوة صغيرة اليوم بتعمل فرق كبير 🌱",
    NotificationMessages::LOW_ACTIVITY_BODY        => "يا {{student_name}} من فترة ما اشتغلت على دروسك. ادخل شوف وين وصلت و ابدأ درس اليوم مشان تضل محافظ على مستواك.",

    NotificationMessages::COMMENT_REPLY_TITLE      => "تم الرد على تعليقك 💬",
    NotificationMessages::COMMENT_REPLY_BODY       => "في رد جديد على تعليقك في درس \"{{lesson_title}}\". ادخل شوف شو الأستاذ رد عليك.",

    NotificationMessages::QUESTION_ANSWER_TITLE    => "المعلم جاوب على سؤالك 🎓",
    NotificationMessages::QUESTION_ANSWER_BODY     => "الأستاذ {{teacher_name}} رد على سؤالك في درس \"{{lesson_title}}\". ادخل شوف الإجابة و استفد منها حتى تكمل دروسك.",
];
