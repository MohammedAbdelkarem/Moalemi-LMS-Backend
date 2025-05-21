<?php

namespace App\Http\Resources\System\Info;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        if ($user && $user->role_id != 3)
            return [
                "id" => $this->id,
                "name_ar" => $this->name_ar,
                "name_en" => $this->name_en,
                "created_at" => Carbon::parse($this->created_at)->translatedFormat("Y-m-d g:i A"),
                "updated_at" => Carbon::parse($this->updated_at)->translatedFormat("Y-m-d g:i A"),
            ];
        return [
            "id" => $this->id,
            "name" => $this["name_" . (app()->getLocale())],
        ];
    }
}
