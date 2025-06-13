<?php

namespace App\Http\Controllers\Patient;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AppointmentRequest;
use App\Http\Requests\Reservation\RateVisitRequest;
use App\Http\Resources\Reservation\ReservationResource;
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

    public function rate(RateVisitRequest $request , $visit_id)
    {
        return success(
            $this->reservationService->rateVisit($visit_id , $request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getReservations(Request $request)
    {
        return success(
            $this->reservationService->getrPateintReservations($request->patient_id , $request),
            ApiMessages::MSG_SUCCESS,
            ReservationResource::class,
            $request->has('per_page')
        );
    }
    public function getReservationDetails($id)
    {
        return success(
            $this->reservationService->getReservationDetails($id),
            ApiMessages::MSG_SUCCESS,
            ReservationResource::class
        );
    }
}
