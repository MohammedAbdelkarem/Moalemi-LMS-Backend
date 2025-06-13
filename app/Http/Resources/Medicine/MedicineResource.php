<?php

namespace App\Http\Resources\Medicine;

use App\Models\MedicineDay;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
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
            'status' => $this->status,
            'end_date' => $this->end_date,
            'other_end_date' => $this->other_end_date,
            'days_to_take' => $this->days_to_take,
            'visit_id' => $this->visit_id,
            'patient_id' => $this->patient_id,
            'is_latest' => $this->is_latest,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_able_to_edit' => treatmentAbleToEdit($this),
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case in_array($routeName, [
                RouteNames::PATIENT_RELATIONS,
                RouteNames::RESERVATION_DETAILS,
                RouteNames::PATIENT_PERMANENT_PROFILE,
                ]):
                $data['medicine_days'] = MedicineDayResource::collection($this->whenLoaded('medicine_days'));
            break;
        }

        return $data;
    }
}
