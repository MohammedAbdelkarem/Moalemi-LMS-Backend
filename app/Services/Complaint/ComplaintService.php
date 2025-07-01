<?php

namespace App\Services\Complaint;

use App\Models\Complaint;
use App\Constants\MediaCollection;
use App\Enums\ComplaintStatusEnum;
use App\Traits\NotificationHelper;
use App\Constants\NotificationMessages;
use App\Enums\Notifications\NotificationTypes;

/**
 * Class ComplaintService.
 */
class ComplaintService
{
    use NotificationHelper;
    public function store($data)
    {
        $Complaint = Complaint::create($data);

        if(isset($data['images']))
            uploadFilesOnMedia($data['images'] , $Complaint , MediaCollection::COMPLAINT_COLLECTION);

        $this->sendDirectNotification(
            auth()->id(),
            NotificationMessages::COMPLAINT_SUBMITTED_TITLE,
            $this->notificationMessage(
                NotificationMessages::COMPLAINT_SUBMITTED_BODY
            ),
            NotificationTypes::COMPLAINTS->value,
            'ar',
            false,
            "",
            [],
            true,
            [],
            false
        );
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

        $this->sendDirectNotification(
            user_id_of_patient($complaint->patient_id),
            NotificationMessages::COMPLAINT_UPDATED_TITLE,
            $this->notificationMessage(
                NotificationMessages::COMPLAINT_UPDATED_BODY
            ),
            NotificationTypes::COMPLAINTS->value,
            'ar',
            false,
            "",
            [],
            true,
            [],
            false
        );
    }
}
