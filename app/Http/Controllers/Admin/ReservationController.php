<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Reservation\ReservationService;
use App\Http\Requests\Reservation\RejectByAdminRequest;
use App\Http\Resources\Reservation\ReservationResource;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ){}

    public function reject_by_admin(RejectByAdminRequest $request , $id)
    {
        return success(
            $this->reservationService->reject_by_admin($id , $request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getReservations(Request $request , $id)
    {
        return success(
            $this->reservationService->getrDoctorReservations($id , $request->all()),
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

    public function getReservationAnalysis($id)
    {
        return success(
            $this->reservationService->getReservationAnalysis($id),
            ApiMessages::MSG_SUCCESS,
        );
    }
}
