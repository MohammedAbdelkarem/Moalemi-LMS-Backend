<?php

namespace App\Http\Resources\Medicine;

use App\Constants\RouteNames;
use App\Http\Resources\Day\DayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineDayResource extends JsonResource
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
            'day_id' => $this->day_id,
            'medicine_id' => $this->medicine_id,
            'created_at' => $this->created_at,
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case in_array($routeName, [
                RouteNames::PATIENT_RELATIONS,
                RouteNames::RESERVATION_DETAILS,
                RouteNames::PATIENT_PERMANENT_PROFILE,
                RouteNames::RESERVATION_DETAILS_FOR_DOCTOR,
                RouteNames::TREATMENT_DETAILS,
                ]):
                $data['medicine_time'] = MedicineTimeResource::collection($this->whenLoaded('medicine_times'));
                $data['day'] = DayResource::make($this->whenLoaded('day'));
            break;
        }

        return $data;
    }
}
