<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Sleep;
use App\Models\Water;
use App\Models\Patient;
use App\Models\WaterTime;
use App\Models\BmiClassification;
use App\Constants\ExceptionMessages;
use App\Services\Base\ContextService;
use App\Models\OwnerPatientWeightHistory;

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

    public function storeWater($data)
    {
        $patient = Patient::findByIdOrFail(owner_id());

        $water['goal'] = water_goal($patient->weight , $patient->is_male , $patient->birth_date);

        $water = Water::firstOrCreate(
            [
                'patient_id' => $patient->id,
                'created_at' => date('Y-m-d')
            ],
            [
                'patient_id' => $patient->id,
                'goal' => $water['goal'],
            ]
        );

        $water->total_amount += $data['amount'];

        $water->save();

        WaterTime::create([
            'water_id' => $water->id,
            'amount' => $data['amount'],
            'time' => $data['time'],
        ]);
    }
    
    public function getWaterHistory()
    {
        
    }

    public function storeSleep($data)
    {
        $existSleep = Sleep::where('patient_id' , owner_id())->where('created_at' , Carbon::today())->first();

        if($existSleep)
            return forbiddenFailure([] , ExceptionMessages::MSG_SLEEP_ALREADY_EXIST);

        Sleep::create([
            'patient_id' => owner_id(),
            'goal' => $data['goal'],
            'total_amount' => $data['total_amount']
        ]);
    }

    public function getSleepHistory()
    {

    }
}
