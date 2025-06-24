<?php

namespace App\Services;

use App\Models\OwnerPatientWeightHistory;
use App\Models\Patient;
use App\Services\Base\ContextService;

/**
 * Class HealthService.
 */
class HealthService
{
    public function __construct(
        protected ContextService $contextService,
    )
    {}
    public function updateWeight($current_weight)
    {
        $patient = Patient::findByIdOrFail(owner_id());

        $this->contextService->createWeightHistory($patient->id , $patient->weight , $current_weight);

        $patient->weight = $current_weight;

        $patient->save();
    }

    public function getWeight($data)
    {
        return getOrPaginate(
            OwnerPatientWeightHistory::where('patient_id' , owner_id()),
            $data
        );
    }
}
