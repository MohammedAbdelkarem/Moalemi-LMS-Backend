<?php

namespace App\Http\Resources\Medicine;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineTimeResource extends JsonResource
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
            'medicine_day_id' => $this->medicine_day_id,
            'time' => $this->time,
            'other_time' => $this->other_time,
            'created_at' => $this->created_at,
        ];

        $routeName = $request->route()->getName();

        // switch ($routeName)
        // {
        //     case RouteNames::EXAMPLE:
        //         $data['foo']   = $this->bar;
        //     break;
        //     case RouteNames::EXAMPLE:
        //         $data['foo']   = $this->bar;
        //     break;
        // }

        return $data;
    }
}
