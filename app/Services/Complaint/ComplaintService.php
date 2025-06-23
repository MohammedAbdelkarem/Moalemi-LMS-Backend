<?php

namespace App\Services\Complaint;

use App\Constants\MediaCollection;
use App\Enums\ComplaintStatusEnum;
use App\Models\Complaint;

/**
 * Class ComplaintService.
 */
class ComplaintService
{
    public function store($data)
    {
        $Complaint = Complaint::create($data);

        if(isset($data['images']))
            uploadFilesOnMedia($data['images'] , $Complaint , MediaCollection::COMPLAINT_COLLECTION);
    }

    public function getForDoctor($doctor_id)
    {
        return Complaint::where('doctor_id' , $doctor_id)->with('patient' , 'doctor' , 'reservation')->get();
    }

    public function getForPatient($patient_id)
    {
        return Complaint::where('patient_id' , $patient_id)->with('patient' , 'doctor' , 'reservation')->get();
    }

    public function process($id)
    {
        $complaint = Complaint::find($id);

        $complaint->status = ComplaintStatusEnum::PROCESSED->value;

        $complaint->save();
    }
}
