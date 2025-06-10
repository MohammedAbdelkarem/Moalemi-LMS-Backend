<?php

namespace App\Services\Reservation;

use App\Models\Shift;
use App\Models\Reservation;
use App\Enums\ReservationStatusEnum;
use App\Services\Base\ContextService;

/**
 * Class ReservationService.
 */
class ReservationService
{
    public function __construct(
        protected ContextService $contextService
    )
    {}
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
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::REJECTED;

        $reservation->rejection_reason = $data['rejection_reason'] ?? null;
        $reservation->other_rejection_reason = $data['other_rejection_reason'] ?? null;

        $reservation->save();
    }

    public function accept($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::ACCEPTED;

        $reservation->time_to_come = $data['time_to_come'];

        $reservation->save();
    }

    public function cancel($id)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::CANCELLED;

        $reservation->save();
    }

    public function did_not_come($id)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::DID_NOT_COME;

        $reservation->save();
    }

    public function done($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        
    }
}
