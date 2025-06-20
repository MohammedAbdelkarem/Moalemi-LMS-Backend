<?php

namespace App\Http\Resources\Rate;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
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
