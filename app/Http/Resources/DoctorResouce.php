<?php

namespace App\Http\Resources;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResouce extends JsonResource
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
            'clinic_name' => $this->clinic_name,
            'address_text' => $this->address_text,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'license_number' => $this->license_number,
            'is_center' => $this->is_center,
            'bio' => $this->bio,
        ];

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
