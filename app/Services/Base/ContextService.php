<?php

namespace App\Services\Base;

use Carbon\Carbon;
use App\Models\Day;
use App\Models\Visit;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Reservation;
use App\Enums\PublishStatusEnum;
use App\Constants\ExceptionMessages;
use App\Models\OwnerPatientWeightHistory;

/**
 * Class ContextService.
 */
class ContextService
{
    public function changePublishStatus($context)
    {
        $context->publish_status =
            ($context->publish_status == PublishStatusEnum::PUBLISHED)
            ? PublishStatusEnum::DRAFT
            : PublishStatusEnum::PUBLISHED;

        $context->save();
    }

    public function checkIfPlanIsPublished($plan)
    {
        if ($plan->publish_status == PublishStatusEnum::DRAFT)
            return unprocessableFailure([], ExceptionMessages::MSG_CAN_NOT_SUBSCRIBE_TO_DRAFT_PLAN);
    }

    public function getUserModel($user)
    {
        if ($user->role_id == 3)
            return Doctor::class;
        else
            return Patient::class;
    }

    public function checkIfReservationEditorIsValid($reservation_id)
    {
        $reservation = Reservation::findbyIdOrFail($reservation_id);

        $valid = true;

        if (auth()->user()->isDoctor())
            $valid = doctor_id() == $reservation->doctor_id;
        else if (auth()->user()->isPatient())
            $valid = user_id_of_patient($reservation->patient_id) == auth()->id();
        else
            $valid = false;

        if (!$valid)
            return unprocessableFailure([], ExceptionMessages::MSG_THIS_IS_NOT_YOUR_ROUTE);
    }

    public function checkIfPatientCanCancelReservation($reservation)
    {
        if (!ableToCancel($reservation))
            return forbiddenFailure([], ExceptionMessages::MSG_CAN_NOT_CANCEL_RESERVATION_CUZ_TIME);
    }

    public function checkIfDoctorCanEditOrChatWithPatient($visit)
    {
        if (!ableToChangeByDoctor($visit))
            return forbiddenFailure([], ExceptionMessages::MSG_CAN_NOT_EDIT_OR_CHAT_WITH_USER);
    }

    public function createWeightHistory($patient_id, $prev_weight, $current_weight)
    {
        OwnerPatientWeightHistory::create([
            'patient_id' => $patient_id,
            'prev_weight' => $prev_weight,
            'current_weight' => $current_weight,
        ]);
    }

    public function getDatesForDay($dayId)
    {
        $day = Day::find($dayId)?->name;

        if (!$day) {
            return []; 
        }

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addYear();

        $dates = [];

        while ($startDate->lte($endDate)) {
            if ($startDate->englishDayOfWeek === $day) {
                $dates[] = $startDate->copy()->toDateString();
            }
            $startDate->addDay();
        }

        return $dates;
    }
}
