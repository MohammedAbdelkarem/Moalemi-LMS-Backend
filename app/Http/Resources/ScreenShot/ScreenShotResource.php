<?php

namespace App\Http\Resources\ScreenShot;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreenShotResource extends JsonResource
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
            'total_number_allowed' => $this->total_number_allowed,
            'total_number_used' => $this->total_number_used,
            'is_disabled' => $this->is_disabled,
            'created_at' => $this->created_at,
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
