<?php

namespace App\Services\Treatment;

use App\Models\Medicine;
use App\Models\Instruction;
use App\Enums\TreatmentStatusEnum;
use App\Constants\ExceptionMessages;
use App\Models\Scopes\LatestTreatmentScope;

/**
 * Class TreatmentService.
 */
class TreatmentService
{
    public function getMedicineHistory($medicine_id , $data)
    {
        $medicine = Medicine::findByIdOrFail($medicine_id);
        // dd($medicine , $medicine_id);
        $this->checkIfHasHistory($medicine);

        $history_ids = $medicine->treatment_history()->pluck('history_ids')->first();
        // dd($history_ids);
        return getOrPaginate(
            Medicine::withoutGlobalScope(LatestTreatmentScope::class)
                ->whereIn('id', $history_ids)
                ->with(relations: [
                    'medicine_days.day',
                    'medicine_days.medicine_times',
                    'userable'
                ]),
                $data
            );
    }
    public function getInstructionHistory($instruction_id , $data)
    {
        $instruction = Instruction::findByIdOrFail($instruction_id);

        $this->checkIfHasHistory($instruction);

        $history_ids = $instruction->treatment_history()->pluck('history_ids')->first();

        return getOrPaginate(
            Instruction::withoutGlobalScope(LatestTreatmentScope::class)
                ->whereIn('id', $history_ids)
                ->with([
                    'userable'
                ]),
                $data
            );
    }

    public function getExpiredTreatments($patient_id , $type , $with , $data)
    {
        $model = ($type == 'medicine')
        ? Medicine::class
        : Instruction::class;

        return getOrPaginate(
            $model::withoutGlobalScope(LatestTreatmentScope::class)
                ->where('patient_id' , $patient_id)
                ->where('status' , TreatmentStatusEnum::EXPIRED->value)
                ->where('is_latest' , 1)
                ->with($with),
                $data
        );
    }


    private function checkIfHasHistory($context)
    {
        if(! hasHistory($context))
        {
            return forbiddenFailure([] , ExceptionMessages::MSG_CAN_NOT_GET_HISTORY_FOR_THE_HISTORY);
        }
    }
}
