<?php

namespace App\Services\Patient;

use App\Enums\TreatmentStatusEnum;
use App\Models\Instruction;
use App\Models\Medicine;
use App\Models\MedicineDay;
use App\Models\MedicineTime;
use App\Models\Patient;
use App\Traits\StorageHelper;

/**
 * Class PatientService.
 */
class PatientService
{
    use StorageHelper;
    public function getMyRelations()
    {
        return Patient::where('user_id', auth()->id())->get();
    }
    public function createMyMedicalProfile($data)
    {
        $patientData = [
            'is_owner' => 1,
            'user_id' => auth()->id(),
            'full_name' => auth()->user()->name ?? $data['full_name'] ?? null,
            'birth_date' => auth()->user()->birth_date ?? $data['birth_date'] ?? null,
            'is_male' => auth()->user()->is_male ?? $data['is_male'] ?? null,
            'avatar' => auth()->user()->avatar ?? null,
            'relation' => 'me',
            'smoking' => $data['smoking'] ?? null,
            'alcohol' => $data['alcohol'] ?? null,
            'current_height' => $data['height'] ?? null,
            'current_weight' => $data['weight'] ?? null,
            'current_blood_type' => $data['blood_type'] ?? null,
            'current_chronic_diseases' => $data['chronic_diseases'] ?? null,
            'current_notes' => $data['notes'] ?? null,
        ];

        $patient = $this->storePatientData($patientData);

        if (isset($data["avatar"]) && $patient->avatar == null) {
            $patient->avatar = $this->storeFile(
                file: $data["avatar"],
                path: "users/{$patient->id}"
            );
        }

        if(isset($data['medicines']))
            $this->storeMedicinesData($data['medicines'] , $patient->id);
        if(isset($data['instructions']))
            $this->storeInstructionsData($data['instructions'] , $patient->id);
    }

    public function createMedicalProfile($data)
    {
        $patientData = [
            'user_id' => auth()->id(),
            'full_name' => $data['full_name'] ?? null,
            'birth_date' =>  $data['birth_date'] ?? null,
            'is_male' => $data['is_male'] ?? null,
            // 'avatar' => $data['avatar'] ?? null,
            'relation' => $data['relation'] ?? null,
            'smoking' => $data['smoking'] ?? null,
            'alcohol' => $data['alcohol'] ?? null,
            'current_height' => $data['height'] ?? null,
            'current_weight' => $data['weight'] ?? null,
            'current_blood_type' => $data['blood_type'] ?? null,
            'current_chronic_diseases' => $data['chronic_diseases'] ?? null,
            'current_notes' => $data['notes'] ?? null,
        ];

        $patient = $this->storePatientData($patientData);

        if (isset($data["avatar"])) {
            $patient->avatar = $this->storeFile(
                file: $data["avatar"],
                path: "users/{$patient->id}"
            );
        }

        if(isset($data['medicines']))
            $this->storeMedicinesData($data['medicines'] , $patient->id);
        if(isset($data['instructions']))
            $this->storeInstructionsData($data['instructions'] , $patient->id);
    }

    private function storePatientData($data)
    {
        $patient = Patient::create($data);

        return $patient;
    }

    private function storeMedicinesData($data , $patient_id = null , $visit_id = null)
    {
        foreach($data as $medicine)
        {
            $medicine['status'] = TreatmentStatusEnum::PERMANENT;
            $medicine['patient_id'] = $patient_id;
            $medicine['visit_id'] = $visit_id;

            $one_medicine = Medicine::create($medicine);

            // dd($medicine);
            if(isset($medicine['days']))
            {
                foreach($medicine['days'] as $one_day)
                {
                    // dd($one_day['day_id']);
                    $medicine_day = MedicineDay::create([
                        'day_id' => $one_day['day_id'],
                        'medicine_id' => $one_medicine->id
                    ]);

                    if(isset($one_day['time']))
                    {
                        foreach($one_day['time'] as $one_time)
                        {
                            // dd($one_time);
                            MedicineTime::create([
                                'medicine_day_id' => $medicine_day->id,
                                'time' => $one_time,
                            ]);
                        }
                    }
                    if(isset($one_day['other_time']))
                    {
                        foreach($one_day['other_time'] as $other_time)
                        {
                            MedicineTime::create([
                                'medicine_day_id' => $medicine_day->id,
                                'other_time' => $other_time,
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function storeInstructionsData($data , $patient_id = null , $visit_id = null)
    {
        foreach($data as $instruction)
        {
            $instruction['status'] = TreatmentStatusEnum::PERMANENT;
            $instruction['patient_id'] = $patient_id;
            $instruction['visit_id'] = $visit_id;
            
            Instruction::create($instruction);
        }
    }

    private function updateMedicalProfile($data , $patient_id)
    {

    }
}
