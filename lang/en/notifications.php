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
    NotificationTypes::AUTH->value           => "Authentication Notifications",
    NotificationTypes::RESERVATIONS->value   => "Reservations Notifications",
    NotificationTypes::MEDICAL_PROFILE->value=> "Medical Profile Notifications",
    NotificationTypes::RATE->value           => "Ratings Notifications",
    NotificationTypes::COMPLAINTS->value     => "Complaints Notifications",
    NotificationTypes::STEPS->value          => "Steps Notifications",
    NotificationTypes::WATER->value          => "Water Notifications",
    NotificationTypes::SLEEP->value          => "Sleep Notifications",
    NotificationTypes::WEIGHT->value         => "Weight Notifications",
    NotificationTypes::GENERAL->value        => "Motivational Notifications",
    NotificationTypes::ARTICLES->value       => "Articles Notifications",

    //
    "Suspend" => "Suspend",
    "Unblock" => "Unblock",

    //Messages
    //Account
    NotificationMessages::LOGIN_TITLE   => "New Login attepmt",
    NotificationMessages::LOGIN_BODY    => "A new login attempt was made from a :device device from :location",
    NotificationMessages::BAN_TITLE     => "Your account has been suspended",
    NotificationMessages::BAN_BODY      => "Your account has been suspended until :bannedUntil due to: :reason",
    NotificationMessages::UNBAN_TITLE   => "Your account has been unblocked",
    NotificationMessages::UNBAN_BODY    => "You can now use all application features, your account has been unblocked",

    //Customer Service Card
    NotificationMessages::CUSTOMER_SERVICE_CARD_DELETE_TITLE    => "Your customer service card has been delete",
    NotificationMessages::CUSTOMER_SERVICE_CARD_DELETE_BODY     => "Your customer service card with the title: ':name' has been deleted",
    NotificationMessages::CUSTOMER_SERVICE_CARD_CLOSE_TITLE     => "Your customer service card has been closed",
    NotificationMessages::CUSTOMER_SERVICE_CARD_CLOSE_BODY      => "Your customer service card with the title: ':name' has been closed",
];
