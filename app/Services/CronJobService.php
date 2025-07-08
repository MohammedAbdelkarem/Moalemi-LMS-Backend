<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Traits\NotificationHelper;
use App\Enums\ReservationStatusEnum;
use App\Constants\NotificationMessages;
use App\Enums\Notifications\NotificationTypes;

/**
 * Class CronJobService.
 */
class CronJobService
{
    use NotificationHelper;
    public function remindForReservationsDaily()
    {
        $reservations = Reservation::whjere('status' , ReservationStatusEnum::ACCEPTED->value)
                ->where('daily_reminded' , 0)
                ->get();

        foreach ($reservations as $reservation) 
        {
            $dateTime = $reservation->date . ' ' . $reservation->time_to_come;

            if(Carbon::now()->diffInHours(Carbon::parse($dateTime)) <= 24)
            {
                $reservation->daily_reminded = 1;
                $reservation->save();

                    $this->sendDirectNotification(
                    user_id_of_patient($reservation->patient_id),
                    $this->notificationMessage(NotificationMessages::APPOINTMENT_REMINDER_24_TITLE),
                    $this->notificationMessage(
                        NotificationMessages::APPOINTMENT_REMINDER_24_BODY,
                        [
                            'name' => $reservation->doctor->clinic_name,
                        ]
                    ),
                    NotificationTypes::RESERVATIONS->value,
                    'ar',
                    false,
                    "",
                    [],
                    true,
                    [],
                    true
                );
            }
        }
    }
    public function remindForReservationsHourly()
    {
        $reservations = Reservation::whjere('status' , ReservationStatusEnum::ACCEPTED->value)
                ->where('hourly_reminded' , 0)
                ->get();

        foreach ($reservations as $reservation) 
        {
            $dateTime = $reservation->date . ' ' . $reservation->time_to_come;

            if(Carbon::now()->diffInHours(Carbon::parse($dateTime)) <= 1)
            {
                $reservation->hourly_reminded = 1;
                $reservation->save();

                    $this->sendDirectNotification(
                    user_id_of_patient($reservation->patient_id),
                    $this->notificationMessage(NotificationMessages::APPOINTMENT_REMINDER_1_TITLE),
                    $this->notificationMessage(
                        NotificationMessages::APPOINTMENT_REMINDER_1_BODY,
                        [
                            'name' => $reservation->doctor->clinic_name,
                        ]
                    ),
                    NotificationTypes::RESERVATIONS->value,
                    'ar',
                    false,
                    "",
                    [],
                    true,
                    [],
                    true
                );
            }
        }
    }
}
