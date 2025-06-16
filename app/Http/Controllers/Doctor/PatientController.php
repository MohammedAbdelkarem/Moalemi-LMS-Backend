<?php

namespace App\Http\Controllers\Doctor;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Patient\PatientService;
use App\Http\Requests\MedicalProfile\AddMedicinesRequest;
use App\Http\Requests\MedicalProfile\UpdateMedicineRequest;
use App\Http\Requests\MedicalProfile\AddInstructionsRequest;
use App\Http\Requests\MedicalProfile\UpdateInstructionRequest;

class PatientController extends Controller
{

    public function __construct(
        protected PatientService $patientService,
    ) {}
    public function addMedicines(AddMedicinesRequest $request , $patient_id , $visit_id)
    {
        return success(
            $this->patientService->addMedicines($request->validated() , $patient_id , $visit_id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function addInstructions(AddInstructionsRequest $request , $patient_id , $visit_id)
    {
        return success(
            $this->patientService->addInstructions($request->validated() , $patient_id , $visit_id),
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
}
