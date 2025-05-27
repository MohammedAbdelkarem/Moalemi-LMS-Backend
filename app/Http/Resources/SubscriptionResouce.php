<?php

namespace App\Http\Resources;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResouce extends JsonResource
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
            'original_price' => $this->original_price,
            'discount_percentage' => $this->discount_percentage,
            'price_after_discount' => $this->price_after_discount,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'number_of_days' => $this->number_of_days,
            'is_active' => $this->is_active,
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
