<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Enums\ReservationStatusEnum;
use App\Http\Resources\DoctorResouce;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Visit\VisitResource;
use App\Http\Resources\Patient\PatientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'text' => $this->text,
            'notes' => $this->notes,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'other_rejection_reason' => $this->other_rejection_reason,
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'shift_start_time' => $this->shift_start_time,
            'shift_end_time' => $this->shift_end_time,
            'date' => $this->date,
            'visits_available' => $this->visits_available,
            'time_to_come' => $this->time_to_come,
            'created_at' => $this->created_at,
        ];

        
        $data['complaints'] = ComplaintResource::collection($this->whenLoaded('complaints'));

        if(auth()->user()->isPatient())
            $data['able_to_cancel'] = ableToCancel($this);

        if(auth()->user()->isDoctor() && $this->status == ReservationStatusEnum::DONE->value)
            $data['is_able_to_edit'] = ableToChangeByDoctor($this->visit);

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::PATIENT_RELATIONS:
                $data['doctor'] = DoctorResouce::make($this->whenLoaded('doctor'));
            break;
            case RouteNames::DOCTOR_RESERVATIONS:
                $data['patient'] = PatientResource::make($this->whenLoaded('patient'));
            break;
            case RouteNames::ADMIN_RESERVATIONS:
                $data['doctor'] = DoctorResouce::make($this->whenLoaded('doctor'));
                $data['patient'] = PatientResource::make($this->whenLoaded('patient'));
            break;
            case RouteNames::PATIENT_RESERVATIONS:
                $data['doctor'] = DoctorResouce::make($this->whenLoaded('doctor'));
                $data['visit'] = VisitResource::make($this->whenLoaded('visit'));
            break;
            case RouteNames::RESERVATION_DETAILS:
                $data['media'] = MediaResource::collection($this->getMedia(MediaCollection::RESERVATION_COLLECTION));
                $data['doctor'] = DoctorResouce::make($this->whenLoaded('doctor'));
                $data['patient'] = PatientResource::make($this->whenLoaded('patient'));
                $data['visit'] = VisitResource::make($this->whenLoaded('visit'));
            break;
        }

        return $data;
    }
}
