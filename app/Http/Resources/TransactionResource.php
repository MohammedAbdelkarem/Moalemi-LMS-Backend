<?php

namespace App\Http\Resources;

use App\Constants\RouteNames;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'amount' => $this->amount,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::ADMIN_TRANSACTION_GET:
                $data['doctor'] = DoctorResouce::make($this->whenLoaded('doctor'));
                $data['subscription']   = SubscriptionResouce::make($this->whenLoaded('subscription'));
            break;
            case RouteNames::DOCTOR_TRANSACTION_GET:
                $data['subscription']   = SubscriptionResouce::make($this->whenLoaded('subscription'));
            break;
        }

        return $data;
    }
}
