<?php

namespace App\Http\Controllers\Patient;

use App\Models\Visit;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Patient\PatientService;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Resources\Patient\PatientResource;
use App\Http\Requests\MedicalProfile\AddMedicinesRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicineRequest;
use App\Http\Requests\MedicalProfile\AddInstructionsRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicinesRequest;
use App\Http\Requests\MedicalProfile\UpdateInstructionRequest;
use App\Http\Requests\MedicalProfile\UpdateInstructionsRequest;
use App\Http\Requests\MedicalProfile\CreateMedicalProfileRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicalProfileRequest;

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
            ApiMessages::MSG_SUCCESS,
            PatientResource::class
        );
    }

    public function updateInfo(UpdateMedicalProfileRequest $request , $id)
    {
        return success(
            $this->patientService->updatePatientInfo($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function addMedicines(AddMedicinesRequest $request , $patient_id)
    {
        return success(
            $this->patientService->addMedicines($request->validated() , $patient_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function addInstructions(AddInstructionsRequest $request , $patient_id)
    {
        return success(
            $this->patientService->addInstructions($request->validated() , $patient_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updateMedicine(UpdateMedicineRequest $request , $patient_id , $medicine_id)
    {
        return success(
            $this->patientService->updateMedicine($request->validated() , $patient_id , $medicine_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updateInstruction(UpdateInstructionRequest $request , $patient_id , $instruction_id)
    {
        return success(
            $this->patientService->updateInstruction($request->validated() , $patient_id , $instruction_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function deleteMedicine($id)
    {
        return success(
            $this->patientService->deleteMedicine($id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function deleteInstruction($id)
    {
        return success(
            $this->patientService->deleteInstruction($id), 
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getPermanentProfile($patient_id)
    {
        return success(
            $this->patientService->getProfileForPermanetTreatments($patient_id),
            ApiMessages::MSG_SUCCESS,
            PatientResource::class
        );
    }

    public function home()
    {
        return success(
            $this->patientService->home(),
            ApiMessages::MSG_SUCCESS,
        );
    }

}
