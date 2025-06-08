<?php

namespace App\Http\Controllers\Patient;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalProfile\CreateMedicalProfileRequest;
use App\Http\Requests\MedicalProfile\UpdateInstructionsRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicalProfileRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicinesRequest;
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

    public function updateInfo(UpdateMedicalProfileRequest $request , $id)
    {
        return success(
            $this->patientService->updatePatientInfo($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updateMedicines(UpdateMedicinesRequest $request , $id)
    {
        return success(
            $this->patientService->updatePermanentMedicines($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updateInstructions(UpdateInstructionsRequest $request , $id)
    {
        return success(
            $this->patientService->updatePermanentInstructions($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }
}
