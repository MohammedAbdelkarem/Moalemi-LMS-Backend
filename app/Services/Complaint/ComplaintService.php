<?php

namespace App\Services\Complaint;

use App\Models\Complaint;

/**
 * Class ComplaintService.
 */
class ComplaintService
{
    public function store($data)
    {
        Complaint::create($data);
    }

    public function getForDoctor($doctor_id)
    {
        return Complaint::where('doctor_id' , $doctor_id)->with('patient' , 'doctor' , 'reservation')->get();
    }

    public function getForPatient($patient_id)
    {
        return Complaint::where('patient_id' , $patient_id)->with('patient' , 'doctor' , 'reservation')->get();
    }
}
