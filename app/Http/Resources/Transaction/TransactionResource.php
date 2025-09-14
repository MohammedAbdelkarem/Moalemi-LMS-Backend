<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\Copon\CopnoResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Context\UnlockedContextResource;

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
            'transaction_type' => $this->transaction_type,
            'cupon' => CopnoResource::make($this->whenLoaded('coupon')),
            'unlocked_context' => UnlockedContextResource::make($this->whenLoaded('unlockedContext')),
        ];

        

        $routeName = $request->route()->getName();


        return $data;
    }
}
