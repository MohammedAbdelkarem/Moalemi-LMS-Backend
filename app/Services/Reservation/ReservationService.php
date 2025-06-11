<?php

namespace App\Services\Reservation;

use App\Enums\ReactionStatusEnum;
use App\Models\Shift;
use App\Models\Visit;
use App\Models\Reservation;
use App\Constants\MediaCollection;
use App\Enums\ReservationStatusEnum;
use App\Models\Patient;
use App\Models\PatientUpdatedInfo;
use App\Services\Base\ContextService;
use App\Services\Patient\PatientService;

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

        $reservation->status = ReservationStatusEnum::REJECTED;

        $reservation->rejection_reason = $data['rejection_reason'] ?? null;
        $reservation->other_rejection_reason = $data['other_rejection_reason'] ?? null;

        $reservation->save();
    }

    public function reject_by_admin($id , $data)
    {
        // $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::REJECTED_BY_ADMIN;

        $reservation->rejection_reason = $data['rejection_reason'] ?? null;
        $reservation->other_rejection_reason = $data['other_rejection_reason'] ?? null;

        $reservation->save();
    }

    public function accept($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::ACCEPTED;

        $reservation->time_to_come = $data['time_to_come'];

        $reservation->save();
    }

    public function cancel($id)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::CANCELLED;

        $reservation->save();
    }

    public function did_not_come($id)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        $reservation = Reservation::findByIdOrFail($id);

        $reservation->status = ReservationStatusEnum::DID_NOT_COME;

        $reservation->save();
    }

    public function done($id , $data)
    {
        $this->contextService->checkIfReservationEditorIsValid($id);

        //report
        $reservation = Reservation::findByIdOrFail($id);

        $visit = Visit::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'doctor_id' => $reservation->doctor_id,
            'patient_id' => $reservation->patient_id,
            'reservation_id' => $id,
            'note' => $data['notes']
        ]);

        if(isset($data['attachments']))
            uploadFilesOnMedia($data['attachments'] , $visit , MediaCollection::VISIT_COLLECTION);

        //patient updated info

        $patient = Patient::find($reservation->patient_id);

        if($data['patient_info_updated'])
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
}
