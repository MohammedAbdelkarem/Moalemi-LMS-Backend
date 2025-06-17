<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AcceptRequest;
use App\Http\Requests\Reservation\RejectRequest;
use App\Http\Requests\Reservation\ReportRequest;
use App\Http\Requests\Reservation\UpdateReportRequest;
use App\Services\Reservation\ReservationService;
use App\Http\Resources\Reservation\ReservationResource;
use App\Models\Doctor;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationService $reservationService
    ){}

    public function reject(RejectRequest $request , $id)
    {
        return success(
            $this->reservationService->reject($id , $request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function accept(AcceptRequest $request , $id)
    {
        return success(
            $this->reservationService->accept($id , $request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function did_not_come($id)
    {
        return success(
            $this->reservationService->did_not_come($id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function done(ReportRequest $request , $id)
    {
        return success(
            $this->reservationService->done($id , $request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getReservations(Request $request)
    {
        return success(
            $this->reservationService->getrDoctorReservations(doctor_id() , $request->all()),
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

    public function updateReport(UpdateReportRequest $request , $visit_id)
    {
        return success(
            $this->reservationService->updateReport($visit_id , $request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }
}
