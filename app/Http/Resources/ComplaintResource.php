<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'reservation_id' => $this->reservation_id,
            'value' => $this->value,
            'other_value' => $this->other_value,
            'status' => $this->status,
        ];

        $data['images'] = MediaResource::collection($this->getMedia(MediaCollection::COMPLAINT_COLLECTION));

        $data['doctor'] = $this->whenLoaded('doctor');
        $data['patient'] = $this->whenLoaded('patient');
        $data['reservation'] = $this->whenLoaded('reservation');
        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::EXAMPLE:
                $data['foo']   = $this->bar;
            break;
            case RouteNames::EXAMPLE:
                $data['foo']   = $this->bar;
            break;
        }

        return $data;
    }
}
