<?php

namespace App\Http\Resources\Instruction;

use App\Models\Patient;
use App\Models\MedicineDay;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\DoctorResouce;
use App\Http\Resources\AddedByResource;
use App\Http\Resources\Patient\PatientResource;
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
        $userable = $this->whenLoaded('userable');

        $data = [
            'id' => $this->id,
            'text' => $this->text,
            'status' => $this->status,
            'end_date' => $this->end_date,
            'other_end_date' => $this->other_end_date,
            'notes' => $this->notes,
            'visit_id' => $this->visit_id,
            'patient_id' => $this->patient_id,
            'is_latest' => $this->is_latest,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'has_history' => hasHistory($this),
            'added_by' => AddedByResource::make($this->userable)
        ];

        
        if(auth()->user()->isPatient())
            $data['is_able_to_edit'] = treatmentAbleToEdit($this);
        // $routeName = $request->route()->getName();

        // switch ($routeName)
        // {
        //     case RouteNames::PATIENT_RELATIONS:
        //         $data['able_to_edit']   = $this->visit_id == null;
        //     break;
        // }

        return $data;
    }
}
