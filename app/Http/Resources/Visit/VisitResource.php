<?php

namespace App\Http\Resources\Visit;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Medicine\MedicineResource;
use App\Http\Resources\Instruction\InstructionResource;

class VisitResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'reservation_id' => $this->reservation_id,
            'note' => $this->note,
            'created_at' => $this->created_at,
        ];

        $routeName = $request->route()->getName();

        if(auth()->user()->isDoctor())
            $data['is_able_to_edit'] = ableToChangeByDoctor($this);

        switch ($routeName)
        {
            case RouteNames::PATIENT_RESERVATIONS:
                $data['rate']   = $this->whenLoaded('rate');
            break;
            case RouteNames::RESERVATION_DETAILS:
                $data['media'] = MediaResource::collection($this->getMedia(MediaCollection::VISIT_COLLECTION));
                $data['rate']   = $this->whenLoaded('rate');
                $data['patientUpdatedInfo'] = $this->whenLoaded('patientUpdatedInfo');
                $data['medicines'] = MedicineResource::collection($this->whenLoaded('medicines'));
                $data['instructions'] = InstructionResource::collection($this->whenLoaded('instructions'));
            case RouteNames::RESERVATION_DETAILS_FOR_DOCTOR:
                $data['media'] = MediaResource::collection($this->getMedia(MediaCollection::VISIT_COLLECTION));
                // $data['rate']   = $this->whenLoaded('rate');
                $data['patientUpdatedInfo'] = $this->whenLoaded('patientUpdatedInfo');
                // $data['medicines'] = MedicineResource::collection($this->whenLoaded('medicines'));
                // $data['instructions'] = InstructionResource::collection($this->whenLoaded('instructions'));
            break;
        }

        return $data;
    }
}
