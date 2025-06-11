<?php

namespace App\Http\Resources\Instruction;

use App\Constants\RouteNames;
use App\Models\MedicineDay;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructionResource extends JsonResource
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
            'notes' => $this->notes,
            'visit_id' => $this->visit_id,
            'patient_id' => $this->patient_id,
            'is_latest' => (bool) $this->is_latest, // Cast to boolean
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        $data['medicine_days'] = MedicineDay::collection($this->whenLoaded('medicineDays'));
        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::PATIENT_RELATIONS:
                $data['able_to_edit']   = $this->visit_id == null;
            break;
        }

        return $data;
    }
}
