<?php

namespace App\Http\Resources\Copon;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CoponLogResource extends JsonResource
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
            'coupon_id' => $this->coupon_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::ADMIN_COPONS_GET:
                $data['user'] = UserResource::make($this->whenLoaded('user'));
            break;
            case RouteNames::ADMIN_STUDENT_PROFILE:
                $data['coupon'] = CopnoResource::make($this->whenLoaded('coupon'));
            break;
        }

        return $data;
    }
}
