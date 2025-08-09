<?php

namespace App\Services\Vaccination;

use App\Models\PatientVaccination;
use Carbon\Carbon;
use App\Models\Patient;
use App\Enums\ChildAgeEnum;
use App\Models\Vaccination;

/**
 * Class VaccinationService.
 */
class VaccinationService
{
    public function createVaccinations($patient)
    {
        $now = Carbon::now();

        $ageInYears = Carbon::parse($patient->birth_date)->diffInYears($now);

        if($ageInYears < 13)
            for ($i = 1; $i <= 8; $i++)
                $patient->vaccinations()->attach([
                    'vaccination_id' => $i,
                ]);
    }

    public function getVaccinations($patient_id)
    {
        $patient = Patient::findByIdOrFail($patient_id);

        return $patient->vaccinations;
    }

    public function updateVaccination($data , $vaccination_id , $patient_id)
    {
        PatientVaccination::where('vaccination_id' , $vaccination_id)
                ->where('patient_id' , $patient_id)
                ->update([
                    'checked' => $data['checked'],
                    'note' => $data['note'],
                ]);
    }

    private function getChildAgeEnum($birthdate)
    {
        $now = Carbon::now();
        $ageInMonths = $birthdate->diffInMonths($now);
        $ageInYears = $birthdate->diffInYears($now);

        return match (true) 
        {
            $ageInMonths < 3                                => ChildAgeEnum::AT_BIRTH,
            $ageInMonths >= 3   &&  $ageInMonths < 5        => ChildAgeEnum::THREE_MONTHS,
            $ageInMonths >= 5   &&  $ageInMonths < 7        => ChildAgeEnum::FIVE_MONTHS,
            $ageInMonths >= 7   &&  $ageInMonths <= 12      => ChildAgeEnum::SEVEN_MONTHS,
            $ageInYears >= 1    &&  $ageInMonths < 18       => ChildAgeEnum::ONE_YEAR,
            $ageInMonths >= 18  &&  $ageInMonths < 24       => ChildAgeEnum::ONE_AND_HALF_YEAR,
            $ageInYears >= 6    &&  $ageInYears < 8         => ChildAgeEnum::FIRST_GRADE,
            $ageInYears >= 11   &&  $ageInYears < 13        => ChildAgeEnum::SIXTH_GRADE,
            default                                         => null,
        };
    }
}
