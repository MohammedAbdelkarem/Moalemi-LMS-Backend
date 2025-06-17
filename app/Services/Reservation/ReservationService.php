<?php

namespace App\Services\Reservation;

use Carbon\Carbon;
use App\Models\Rate;
use App\Models\Shift;
use App\Models\Visit;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Reservation;
use App\Enums\ReactionStatusEnum;
use App\Constants\MediaCollection;
use App\Models\PatientUpdatedInfo;
use App\Constants\ExceptionMessages;
use App\Enums\ReservationStatusEnum;
use App\Http\Resources\DoctorResouce;
use App\Services\Base\ContextService;
use App\Services\Patient\PatientService;
use App\Http\Resources\Media\MediaResource;

/**
 * Class ReservationService.
 */
class ReservationService
{
    public function __construct(
        protected ContextService $contextService,
        protected PatientService $patientService
    )
    {}
    public function appoint($data)
    {
        if($data['shift_id'])
        {
            $shift = Shift::findByIdOrFail($data['shift_id']);

            $data['shift_start_time'] = $shift->start_time;
            $data['shift_end_time'] = $shift->end_time;
        }

        $reservation = Reservation::create($data);

        if(isset($data['images']))
            uploadFilesOnMedia($data['images'] , $reservation , MediaCollection::RESERVATION_COLLECTION);
    }

    public function reject($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);
        
        $this->checkStatusFlow($reservation->status , ReservationStatusEnum::REJECTED->value);

        $reservation->status = ReservationStatusEnum::REJECTED;

        $reservation->rejection_reason = $data['rejection_reason'] ?? null;
        $reservation->other_rejection_reason = $data['other_rejection_reason'] ?? null;

        $reservation->save();
    }

    public function reject_by_admin($id , $data)
    {
        // $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $this->checkStatusFlow($reservation->status , ReservationStatusEnum::REJECTED_BY_ADMIN->value);

        $reservation->status = ReservationStatusEnum::REJECTED_BY_ADMIN;

        $reservation->rejection_reason = $data['rejection_reason'] ?? null;
        $reservation->other_rejection_reason = $data['other_rejection_reason'] ?? null;

        $reservation->save();
    }

    public function accept($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $this->checkStatusFlow($reservation->status , ReservationStatusEnum::ACCEPTED->value);

        $reservation->status = ReservationStatusEnum::ACCEPTED;

        $reservation->time_to_come = $data['time_to_come'];

        $reservation->save();
    }

    public function cancel($id)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $this->checkStatusFlow($reservation->status , ReservationStatusEnum::CANCELLED->value);

        $this->checkIfPatientCanCancelReservation($reservation);
        
        $reservation->status = ReservationStatusEnum::CANCELLED;

        $reservation->save();
    }

    public function did_not_come($id)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $this->checkStatusFlow($reservation->status , ReservationStatusEnum::DID_NOT_COME->value);

        $reservation->status = ReservationStatusEnum::DID_NOT_COME;

        $reservation->save();
    }

    public function done($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        //report
        $reservation = Reservation::findByIdOrFail($id);

        $this->checkStatusFlow($reservation->status , ReservationStatusEnum::DONE->value);

        $reservation->status = ReservationStatusEnum::DONE;

        $reservation->save();

        $visit = Visit::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'doctor_id' => $reservation->doctor_id,
            'patient_id' => $reservation->patient_id,
            'reservation_id' => $id,
            'note' => $data['notes'] ?? null,
        ]);

        if(isset($data['attachments']))
            uploadFilesOnMedia($data['attachments'] , $visit , MediaCollection::VISIT_COLLECTION);

        //patient updated info

        $patient = Patient::find($reservation->patient_id);

        if($data['patient_info_updated'])
        {
            $this->storePatientUpdatedInfo($visit , $patient , $data);    
        
            $this->updatePatientTableInfo($data , $patient);
        }

        //the next reservation
        if(isset($data['next_date']))
        {
            Reservation::create([
                'text' => $data['next_text'] ?? null,
                'notes' => $data['next_notes'] ?? null,
                'date'  => $data['next_date'] ?? null,
                'time_to_come' => $data['time_to_come'],
                'status' => ReservationStatusEnum::ACCEPTED,
                'patient_id' => $reservation->patient_id,
                'doctor_id' => $reservation->doctor_id,
            ]);
        }

        //medicines and intructions
        if(isset($data['medicines']))
            $this->patientService->storeMedicinesData($data , $patient->id , $visit->id);
        if(isset($data['instructions']))
            $this->patientService->storeInstructionsData($data , $patient->id , $visit->id);
    }

    public function updateReport($visit_id , $data)
    {
        $visit = Visit::findByIdOrFail($visit_id);

        $patient = Patient::findByIdOrFail($visit->patient_id);

        $visit->update([
            'title' => $data['title'] ?? $visit->title,
            'description' => $data['description'] ?? $visit->description,
            'note' => $data['notes'] ?? $visit->notes
        ]);

        if($data['patient_info_updated'])
        {
            $recordExist = $visit->patientUpdatedInfo()->where('visit_id' , $visit->id)->exists();

            if(!$recordExist)
                $this->storePatientUpdatedInfo($visit , $patient , $data);
            else
                $visit->patientUpdatedInfo()->update([
                    'current_height' => $data['new_height'] ?? null,
                    'current_weight' => $data['new_weight'] ?? null,
                    'current_blood_type' => $data['new_blood_type'] ?? null,
                    'current_chronic_diseases' => $data['new_chronic_diseases'] ?? null,
                    'current_notes' => $data['new_notes'] ?? null,
                ]);
            
            $this->updatePatientTableInfo($data , $patient);
        }
    }

    private function storePatientUpdatedInfo($visit , $patient , $data)
    {
        $visit->patientUpdatedInfo()->create([
            'old_height' => $patient->height,
            'old_weight' => $patient->weight,
            'old_blood_type' => $patient->blood_type,
            'old_chronic_diseases' => $patient->chronic_diseases,
            'old_notes' => $patient->notes,
            
            'current_height' => $data['new_height'] ?? null,
            'current_weight' => $data['new_weight'] ?? null,
            'current_blood_type' => $data['new_blood_type'] ?? null,
            'current_chronic_diseases' => $data['new_chronic_diseases'] ?? null,
            'current_notes' => $data['new_notes'] ?? null,
        ]);
    }

    private function updatePatientTableInfo($data , $patient)
    {
        $infos = [
            'height',
            'weight',
            'blood_type',
            'chronic_diseases',
            'notes'
        ];
        
        foreach($infos as $info)
            $patient->$info = $data['new_' . $info] ?? $patient->$info;

        $patient->save();
    }

    public function rateVisit($visit_id , $data)
    {
        $visit = Visit::findByIdOrFail($visit_id);

        $this->checkIfHasBeenRated($visit);

        $rate = Rate::create([
            'patient_id' => $visit->patient_id,
            'doctor_id' => $visit->doctor_id,
            'visit_id' => $visit->id,
            'rate' => $data['rate'],
            'comment' => $data['comment'] ?? null,
        ]);

        if(isset($data['image']))
            uploadFileOnMedia($data['image'] , $rate , MediaCollection::RATE_COLLECTION);

        $doctor = Doctor::find($visit->doctor_id);

        $doctor->rate_sum += $data['rate'];
        $doctor->rate_counter++;
        $doctor->total_rate = $doctor->rate_sum / $doctor->rate_counter;

        $doctor->save();
    }

    private function getReservations($doctor_id , $patient_ids , $data , $with = [])
    {
        return getOrPaginate(
            Reservation::filter($data , $doctor_id , $patient_ids)
            ->with($with),
            $data
        );
    }

    public function getUserReservations($data)
    {
        $patient_ids = Patient::where('user_id' , auth()->id())->pluck('id');
        
        return $this->getReservations(null  ,$patient_ids , $data , [
            'doctor.subCategories',
            'visit.rate'
        ]);
    }

    public function getrDoctorReservations($doctor_id , $data)
    {
        return $this->getReservations($doctor_id , null , $data , [
            'patient',
        ]);
    }

    public function getReservationDetails($reservation_id)
    {
        $reservation = Reservation::findByIdOrFail($reservation_id , [
             'doctor.subCategories' ,
              'patient' ,
                'visit.rate' ,
                 'visit.patientUpdatedInfo' ,
                  'visit.medicines.medicine_days.day' ,
                  'visit.medicines.medicine_days.medicine_times' ,
                   'visit.instructions'
        ]);

        return $reservation;
    }

    private function checkStatusFlow($old_status , $new_status)
    {
        if(
            $old_status == ReservationStatusEnum::PENDING->value &&
             ($new_status == ReservationStatusEnum::DONE->value || $new_status == ReservationStatusEnum::DID_NOT_COME->value)

            || 

            $new_status == ReservationStatusEnum::DONE->value && $old_status != ReservationStatusEnum::ACCEPTED->value

            ||
            
            $old_status == ReservationStatusEnum::ACCEPTED->value && $new_status == ReservationStatusEnum::REJECTED->value

            ||

            (
                $old_status == ReservationStatusEnum::REJECTED->value
            || $old_status == ReservationStatusEnum::CANCELLED->value 
            || $old_status == ReservationStatusEnum::DONE->value 
            || $old_status == ReservationStatusEnum::DID_NOT_COME->value 
            || $old_status == ReservationStatusEnum::REJECTED_BY_ADMIN->value 
            )
        )
        {
            return forbiddenFailure([] , __(ExceptionMessages::MSG_RESERVATION_STATUS_FLOW_ERROR , ['old_status' => $old_status , 'new_status' => $new_status]));
        }
    }

    private function checkIfHasBeenRated($visit)
    {
        if($visit->rate()->exists())
            return forbiddenFailure([] , ExceptionMessages::MSG_CAN_NOT_RATE_AGAIN);
    }

    private function checkIfPatientCanCancelReservation($reservation)
    {
        if(!ableToCancel($reservation))
            return forbiddenFailure([] , ExceptionMessages::MSG_CAN_NOT_CANCEL_RESERVATION_CUZ_TIME);
    }

    private function checkIfDoctorCanEditOrChatWithPatient($visit)
    {
        if(!ableToChangeByDoctor($visit))
            return forbiddenFailure([] , ExceptionMessages::MSG_CAN_NOT_EDIT_OR_CHAT_WITH_USER);
    }
}
