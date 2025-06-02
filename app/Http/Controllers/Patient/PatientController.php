<?php

namespace App\Http\Controllers\Patient;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalProfile\CreateMedicalProfileRequest;
use App\Services\Patient\PatientService;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct(
        protected PatientService $patientService,
    ) {}

    public function createMyMedicalProfile(CreateMedicalProfileRequest $request)
    {
        return createdSuccess(
            $this->patientService->createMyMedicalProfile($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function createMedicalProfile(CreateMedicalProfileRequest $request)
    {
        return createdSuccess(
            $this->patientService->createMedicalProfile($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getRelations()
    {
        return success(
            $this->patientService->getMyRelations(),
            ApiMessages::MSG_SUCCESS
        );
    }
}
