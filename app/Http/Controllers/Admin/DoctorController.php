<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResouce;
use App\Services\Doctor\DoctorService;
use App\Services\Reservation\ReservationService;

class DoctorController extends Controller
{
    public function __construct(
        protected DoctorService $doctorService,
        protected ReservationService $reservationService,
    ) {}

    public function getAll(Request $request)
    {
        return success(
            $this->doctorService->getAll( $request->all()),
            ApiMessages::MSG_SUCCESS,
            DoctorResouce::class,
            $request->has('per_page')
        );
    }

    public function profile($id)
    {
        return success(
            $this->doctorService->getDoctorProfile($id),
            ApiMessages::MSG_SUCCESS,
            DoctorResouce::class
        );
    }

    public function deleteRate($id)
    {
        return success(
            $this->reservationService->deleteRate($id),
            ApiMessages::MSG_SUCCESS,
        );
    }
}
