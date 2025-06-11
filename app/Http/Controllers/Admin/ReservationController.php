<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\RejectByAdminRequest;
use App\Services\Reservation\ReservationService;

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
}
