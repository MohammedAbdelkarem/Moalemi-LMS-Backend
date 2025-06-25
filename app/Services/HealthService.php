<?php

namespace App\Services;

use App\Models\BmiClassification;
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

    public function getWeightHistory($data)
    {
        return getOrPaginate(
            OwnerPatientWeightHistory::where('patient_id' , owner_id()),
            $data
        );
    }

    public function BMI()
    {
        $patient = Patient::findByIdOrFail(owner_id());

        $bmi = BMI($patient->weight , $patient->height);
        $classification = $this->getClassification($bmi);

        return [
            'BMI' => $bmi,
            'classification' => $classification
        ];
    }

    public function getClassification($bmi)
    {
        return BmiClassification::where('start' , '<=' , $bmi)
                            ->where('end' , '>=' , $bmi)
                            ->first();
    }

    public function getWaterGoal()
    {
        $patient = Patient::findByIdOrFail(owner_id());

        return water_goal($patient->weight , $patient->is_male , $patient->birth_date);
    }
}
