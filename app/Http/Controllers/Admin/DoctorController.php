<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResouce;
use App\Services\Doctor\DoctorService;

class DoctorController extends Controller
{
    public function __construct(
        protected DoctorService $doctorService,
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
}
