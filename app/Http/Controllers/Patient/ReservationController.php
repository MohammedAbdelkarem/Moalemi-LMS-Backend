<?php

namespace App\Http\Controllers\Patient;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AppointmentRequest;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ){}

    public function appoint(AppointmentRequest $request)
    {
        return success(
            $this->reservationService->appoint($request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function cancel($id)
    {
        return success(
            $this->reservationService->cancel($id),
            ApiMessages::MSG_SUCCESS
        );
    }
}
