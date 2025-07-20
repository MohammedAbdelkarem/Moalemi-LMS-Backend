<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Visit;
use App\Models\Medicine;
use App\Models\Reservation;
use Faker\Provider\Medical;
use App\Enums\DaysToTakeEnum;
use App\Traits\NotificationHelper;
use App\Enums\ReservationStatusEnum;
use App\Constants\NotificationMessages;
use App\Enums\Notifications\NotificationTypes;
use App\Models\MedicineDay;
use App\Models\MedicineTime;

/**
 * Class CronJobService.
 */
class CronJobService
{
    use NotificationHelper;
    public function remindForReservationsDaily()
    {
        Reservation::where('status', ReservationStatusEnum::ACCEPTED->value)
            ->where('daily_reminded', 0)
            ->chunkById(100, function ($reservations) {
                foreach ($reservations as $reservation) {
                    $dateTime = $reservation->date . ' ' . $reservation->time_to_come;

                    if (Carbon::now()->diffInHours(Carbon::parse($dateTime)) <= 24) {
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
            });
    }

    public function remindForReservationsHourly()
    {
        Reservation::where('status', ReservationStatusEnum::ACCEPTED->value)
            ->where('hourly_reminded', 0)
            ->chunkById(100, function ($reservations) {
                foreach ($reservations as $reservation) {
                    $dateTime = $reservation->date . ' ' . $reservation->time_to_come;

                    if (Carbon::now()->diffInHours(Carbon::parse($dateTime)) <= 1) {
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
            });
    }

    public function remindForVisitsRating()
    {
        Visit::where('rate_reminded',0)
            ->chunkById(100, function ($visits) {
                foreach ($visits as $visit) {

                    if (Carbon::now()->diffInHours(Carbon::parse($visit)) >= 24 ) {

                        $visit->rate_reminded = 1;
                        $visit->save();
                        
                        $this->sendDirectNotification(
                            user_id_of_patient($visit->patient_id),
                            $this->notificationMessage(NotificationMessages::APPOINTMENT_RATING_TITLE),
                            $this->notificationMessage(
                                NotificationMessages::APPOINTMENT_RATING_BODY,
                                [
                                    'name' => $visit->doctor->clinic_name,
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
            });
    }

    public function remindForMedicinesTimes()
    {
        $medicines_ids = Medicine::where('days_to_take', '!=', DaysToTakeEnum::WHEN_NEEDED->value)
            ->pluck('id')
            ->toArray();

        $todayName = Carbon::today()->englishDayOfWeek;
        $nowTime = Carbon::now()->format('H:i:s');

        MedicineDay::whereIn('medicine_id', $medicines_ids)
            ->chunkById(100, function ($medicineDays) use ($todayName, $nowTime) {
                foreach ($medicineDays as $medicineDay) {
                    if ($medicineDay->day->name === $todayName) {
                        MedicineTime::where('medicine_day_id', $medicineDay->id)
                            ->where('daily_reminded', 0)
                            ->whereNotNull('time')
                            ->where('time', '<=', $nowTime)
                            ->chunkById(50, function ($times) {
                                foreach ($times as $medicineTime) {
                                    $medicineTime->update(['daily_reminded' => 1]);

                                    $this->sendDirectNotification(
                                        user_id_of_patient($medicineTime->medicine_day->medicine->patient_id),
                                        $this->notificationMessage(NotificationMessages::MEDICATION_REMINDER_TITLE),
                                        $this->notificationMessage(
                                            NotificationMessages::MEDICATION_REMINDER_BODY,
                                            [
                                                'medication' => $medicineTime->medicine_day->medicine->text,
                                            ]
                                        ),
                                        NotificationTypes::TREATMENT_REMINDER->value,
                                        'ar',
                                        false,
                                        "",
                                        [],
                                        true,
                                        [],
                                        true
                                    );
                                }
                            });
                    }
                }
            });
    }


    //cronjob function to put the medicines reminded_daily as 0 again , at 00:00
    public function resetDailyReminders()
    {
        MedicineTime::where('daily_reminded', 1)
            ->update(['daily_reminded' => 0]);
    }

}
