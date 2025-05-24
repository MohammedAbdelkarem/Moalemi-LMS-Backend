<?php

namespace App\Http\Resources;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'title' => $this->title,
            'price' => $this->price,
            'discount_percentage' => $this->discount_percentage,
            'number_of_days' => $this->number_of_days,
            'discount_start_at' => $this->discount_start_at,
            'discount_end_at' => $this->discount_end_at,
            'price_after_discount' => ($this->discount_percentage > 0 && $this->discount_end_at >= now()) ? $this->price - ($this->price * ($this->discount_percentage / 100)) : $this->price,
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::PLAN_ADMIN:
                $data['publish_status']   = $this->publish_status;
                $data['subscriptions']   = $this->doctors;
            break;
        }

        return $data;
    }
}
