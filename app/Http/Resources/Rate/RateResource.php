<?php

namespace App\Http\Resources\Rate;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\Patient\PatientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'rate' => $this->rate,
            'comment' => $this->comment,
            'doctor_replay' => $this->doctor_replay,
        ];

        $data['patient'] = PatientResource::make($this->whenLoaded('patient'));

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            // case RouteNames::EXAMPLE:
            //     $data['foo']   = $this->bar;
            // break;
            // case RouteNames::EXAMPLE:
            //     $data['foo']   = $this->bar;
            // break;
        }

        return $data;
    }
}
