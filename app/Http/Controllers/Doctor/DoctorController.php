<?php

namespace App\Http\Controllers\Doctor;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\PhoneNumbers\CreatePhoneNumbersRequest;
use App\Http\Requests\Doctor\PhoneNumbers\UpdatePhoneNumbersRequest;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function __construct(
        protected DoctorService $doctorService,
    ) {}

    public function addPhoneNumbers(CreatePhoneNumbersRequest $request)
    {
        return createdSuccess(
            $this->doctorService->addPhoneNumbers($request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function deletePhoneNumbers(Request $request)
    {
        return success(
            $this->doctorService->deletePhoneNumbers($request->all()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updatePhoneNumber($id , UpdatePhoneNumbersRequest $request)
    {
        return success(
            $this->doctorService->updatePhoneNumber($id , $request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }
}
