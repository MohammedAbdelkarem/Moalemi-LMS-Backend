<?php

namespace App\Http\Resources\Patient;

use App\Traits\ImagesHelper;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Instruction\InstructionResource;
use App\Http\Resources\Medicine\MedicineResource;
use App\Http\Resources\Reservation\ReservationResource;

class PatientResource extends JsonResource
{
    use ImagesHelper;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'is_owner' => $this->is_owner,
            'is_owner_medical_profile' => $this->is_owner,
            'user_id' => $this->user_id,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date,
            'is_male' => $this->is_male,
            'relation' => $this->relation,
            'avatar' => $this->getProfileImage($this), // Use asset() to generate a URL
            'smoking' => $this->smoking_status,
            'alcohol' => $this->alcohol_consumption,
            'height' => $this->height_cm,
            'weight' => $this->weight_kg,
            'blood_type' => $this->blood_type,
            'chronic_diseases' => $this->chronic_diseases,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::PATIENT_RELATIONS:
                $data['instructions'] = InstructionResource::collection($this->whenLoaded('instructions'));
                $data['medicines'] = MedicineResource::collection($this->whenLoaded('medicines'));
                $data['reservations'] = ReservationResource::collection($this->whenLoaded('reservations'));
            break;
            case RouteNames::PATIENT_PERMANENT_PROFILE:
                $data['instructions'] = InstructionResource::collection($this->whenLoaded('permanent_instructions'));
                $data['medicines'] = MedicineResource::collection($this->whenLoaded('permanent_medicines'));
            break;
        }

        return $data;
    }
}
