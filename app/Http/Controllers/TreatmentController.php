<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Resources\Instruction\InstructionResource;
use App\Services\Treatment\TreatmentService;
use App\Http\Resources\Medicine\MedicineResource;

class TreatmentController extends Controller
{
    public function __construct(
        protected TreatmentService $treatmentService
    ) {}

    public function getMedicineHistory(Request $request , $medicine_id)
    {
        return success(
            $this->treatmentService->getMedicineHistory($medicine_id , $request->all()),
            ApiMessages::MSG_SUCCESS,
            MedicineResource::class,
            $request->has('per_page')
        );
    }

    public function getInstructionHistory(Request $request , $instruction_id)
    {
        return success(
            $this->treatmentService->getInstructionHistory($instruction_id , $request->all()),
            ApiMessages::MSG_SUCCESS,
            InstructionResource::class,
            $request->has('per_page')
        );
    }
    public function getExpiredMedicine(Request $request , $patient_id)
    {
        return success(
            $this->treatmentService->getExpiredTreatments($patient_id , 'medicine' , ['userable' , 'medicine_days.day' , 'medicine_days.medicine_times'] , $request->all()),
            ApiMessages::MSG_SUCCESS,
            MedicineResource::class,
            $request->has('per_page')
        );
    }

    public function getExpiredInstruction(Request $request , $patient_id)
    {
        return success(
            $this->treatmentService->getExpiredTreatments($patient_id , 'instruction' , ['userable'] , $request->all()),
            ApiMessages::MSG_SUCCESS,
            InstructionResource::class,
            $request->has('per_page')
        );
    }
}
