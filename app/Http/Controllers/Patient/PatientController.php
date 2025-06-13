<?php

namespace App\Http\Controllers\Patient;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Media\UpdateMediaRequest;
use App\Http\Requests\MedicalProfile\AddInstructionsRequest;
use App\Http\Requests\MedicalProfile\AddMedicinesRequest;
use App\Http\Requests\MedicalProfile\CreateMedicalProfileRequest;
use App\Http\Requests\MedicalProfile\UpdateInstructionRequest;
use App\Http\Requests\MedicalProfile\UpdateInstructionsRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicalProfileRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicineRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicinesRequest;
use App\Http\Resources\Patient\PatientResource;
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
            $this->patientService->addMedicinesByPatient($request->validated() , $patient_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function addInstructions(AddInstructionsRequest $request , $patient_id)
    {
        return success(
            $this->patientService->addInstructionsByPatient($request->validated() , $patient_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updateMedicine(UpdateMedicineRequest $request , $patient_id , $medicine_id)
    {
        return success(
            $this->patientService->updateMedicinesByPatient($request->validated() , $patient_id , $medicine_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function updateInstruction(UpdateInstructionRequest $request , $patient_id , $instruction_id)
    {
        return success(
            $this->patientService->updateInstructionsByPatient($request->validated() , $patient_id , $instruction_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function deleteMedicine($id)
    {
        return success(
            $this->patientService->deleteMedicineByPateint($id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function deleteInstruction($id)
    {
        return success(
            $this->patientService->deleteInstructionByPateint($id), 
            ApiMessages::MSG_SUCCESS
        );
    }

}
