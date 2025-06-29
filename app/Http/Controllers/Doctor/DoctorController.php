<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResouce;
use App\Services\Doctor\DoctorService;
use App\Http\Requests\Media\DeleteMediaRequest;
use App\Http\Requests\Doctor\UploadCertificateRequest;
use App\Http\Requests\Doctor\PhoneNumbers\CreatePhoneNumbersRequest;
use App\Http\Requests\Doctor\PhoneNumbers\UpdatePhoneNumbersRequest;

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

    public function getMyProfile()
    {
        return success(
            $this->doctorService->getDoctorProfile(doctor_id()),
            ApiMessages::MSG_SUCCESS,
            DoctorResouce::class
        );
    }

    public function storeCertificate(UploadCertificateRequest $request)
    {
        return success(
            $this->doctorService->uploadCertificateMedia($request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }
    
    public function deleteCertificate(DeleteMediaRequest $request)
    {
        return success(
            $this->doctorService->deleteCertificateMedia($request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function home()
    {
        return success(
            $this->doctorService->home(),
            ApiMessages::MSG_SUCCESS
        );
    }
}
