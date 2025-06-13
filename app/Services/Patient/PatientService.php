<?php

namespace App\Services\Patient;

use App\Constants\ExceptionMessages;
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
        $patients = Patient::where('user_id', auth()->id())->with([
            'instructions' ,
               'medicines.medicine_days.day' ,
                'medicines.medicine_days.medicine_times' ,
                  'reservations.doctor.subCategories',
            ])->get();

        return $patients;
    }
    public function createMyMedicalProfile($data)
    {
        $hasBeenCreated = Patient::where('user_id', auth()->id())
                        ->where('is_owner' , 1)
                        ->exists();
        if($hasBeenCreated)
            return forbiddenFailure([] , ExceptionMessages::MSG_MEDICAL_PROFILE_ALREADY_EXIST);

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
            'height' => $data['height'] ?? null,
            'weight' => $data['weight'] ?? null,
            'blood_type' => $data['blood_type'] ?? null,
            'chronic_diseases' => $data['chronic_diseases'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        $patient = $this->storePatientData($patientData);

        if (isset($data["avatar"]) && $patient->avatar == null) {
            $patient->avatar = $this->storeFile(
                file: $data["avatar"],
                path: "users/{$patient->id}"
            );
        }

        if(isset($data['medicines']))
            $this->storeMedicinesData($data , $patient->id);
        if(isset($data['instructions']))
            $this->storeInstructionsData($data , $patient->id);

        $patient->save();
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
            'height' => $data['height'] ?? null,
            'weight' => $data['weight'] ?? null,
            'blood_type' => $data['blood_type'] ?? null,
            'chronic_diseases' => $data['chronic_diseases'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        $patient = $this->storePatientData($patientData);

        if (isset($data["avatar"])) {
            $patient->avatar = $this->storeFile(
                file: $data["avatar"],
                path: "users/{$patient->id}"
            );
        }

        if(isset($data['medicines']))
            $this->storeMedicinesData($data , $patient->id);
        if(isset($data['instructions']))
            $this->storeInstructionsData($data , $patient->id);

        $patient->save();
    }

    public function updatePatientInfo($data , $patient_id)
    {
        if($patient_id == owner_id())
        {
            $data['relation'] = 'me';
        }

        $patient = Patient::findByIdOrFail($patient_id);

        $patient->update($data);

        if (isset($data["avatar"])) {
                $patient = $this->StoreUpdate(
                file: $data["avatar"],
                path: "patients/{$patient->id}",
                model: $patient,
                column: "avatar",
                deleteImage: true,
                singleFilePath: $patient->avatar ?? ""
            );
        }

        $patient->save();
    }

    public function addMedicinesByPatient($data , $patient_id)
    {
        $this->storeMedicinesData($data , $patient_id);
    }

    public function addInstructionsByPatient($data , $patient_id)
    {
        $this->storeInstructionsData($data , $patient_id);
    }

    public function updateMedicinesByPatient($data , $patient_id , $medicine_id)
    {
        $this->deleteMedicineByPateint($medicine_id);

        $finalData['medicines'][0] = $data;

        $this->storeMedicinesData($finalData , $patient_id);
    }

    public function updateInstructionsByPatient($data , $patient_id , $instruction_id)
    {
        $this->deleteInstructionByPateint($instruction_id);

        $finalData['instructions'][0] = $data;

        $this->storeInstructionsData($finalData , $patient_id);
    }

    public function deleteMedicineByPateint($medicine_id)
    {
        $medicine = Medicine::findByIdOrFail($medicine_id);

        $this->checkIfCanEditTreatments($medicine);

        $medicine->delete();
    }

    public function deleteInstructionByPateint($instruction_id)
    {
        $instruction = Instruction::findByIdOrFail($instruction_id);

        $this->checkIfCanEditTreatments($instruction);

        $instruction->delete();
    }

    private function storePatientData($data)
    {
        $patient = Patient::create($data);

        return $patient;
    }

    public function storeMedicinesData($data , $patient_id = null , $visit_id = null)
    {
        // dd($data);
        foreach($data['medicines'] as $medicine)
        {
            $medicine['patient_id'] = $patient_id;
            $medicine['visit_id'] = $visit_id;
            // dd($medicine);
            $one_medicine = Medicine::create($medicine);

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

    public function storeInstructionsData($data , $patient_id = null , $visit_id = null)
    {
        foreach($data['instructions'] as $instruction)
        {
            $instruction['patient_id'] = $patient_id;
            $instruction['visit_id'] = $visit_id;
            
            Instruction::create($instruction);
        }
    }

    public function getProfileForPermanetTreatments($patient_id)
    {
        return Patient::findByIdOrFail($patient_id , [
            'permanent_instructions' , 
            'permanent_medicines'
        ]);
    }

    private function checkIfCanEditTreatments($context)
    {
        if($context->visit_id != null)
        {
            return forbiddenFailure([] , ExceptionMessages::MSG_CANT_EDIT_TREATMENTS_IN_VISIT);
        }
    }

}
