<?php

namespace App\Services\Treatment;

use App\Models\Medicine;
use App\Constants\ExceptionMessages;
use App\Models\Instruction;
use App\Models\Scopes\LatestTreatmentScope;

/**
 * Class TreatmentService.
 */
class TreatmentService
{
    public function getMedicineHistory($medicine_id , $data)
    {
        $medicine = Medicine::findByIdOrFail($medicine_id);

        $this->checkIfHasHistory($medicine);

        $history_ids = $medicine->treatment_history->history_ids;

        return getOrPaginate(
            Medicine::withoutGlobalScope(LatestTreatmentScope::class)
                ->whereIn('id', $history_ids)
                ->with([
                    'medicine_days.day',
                    'medicine_days.medicine_times'
                ]),
                $data
            );
    }
    public function getInstructionHistory($instruction_id , $data)
    {
        $instruction = Instruction::findByIdOrFail($instruction_id);

        $this->checkIfHasHistory($instruction);

        $history_ids = $instruction->treatment_history->history_ids;

        return getOrPaginate(
            Instruction::withoutGlobalScope(LatestTreatmentScope::class)
                ->whereIn('id', $history_ids),
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
