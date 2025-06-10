<?php

namespace App\Services\Reservation;

use App\Models\Shift;
use App\Models\Reservation;
use App\Enums\ReservationStatusEnum;

/**
 * Class ReservationService.
 */
class ReservationService
{
    public function appoint($data)
    {
        if($data['shift_id'])
        {
            $shift = Shift::findByIdOrFail($data['shift_id']);

            $data['shift_start_time'] = $shift->start_time;
            $data['shift_end_time'] = $shift->end_time;
        }

        Reservation::create($data);
    }

    public function reject($id , $data)
    {
        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::REJECTED;

        $reservation->rejection_reason = $data['rejection_reason'] ?? null;
        $reservation->other_rejection_reason = $data['other_rejection_reason'] ?? null;

        $reservation->save();
    }

    public function accept($id)
    {
        $reservation = Reservation::findByIdOrFail($id);

        
    }
}
