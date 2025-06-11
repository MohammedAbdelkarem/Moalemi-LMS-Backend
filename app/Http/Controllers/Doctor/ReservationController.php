<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Requests\Reservation\ReportRequest;
use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\AcceptRequest;
use App\Http\Requests\Reservation\RejectRequest;
use App\Services\Reservation\ReservationService;

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
}
