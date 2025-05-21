<?php

namespace App\Http\Resources\Users\Profile;

use App\Constants\ApiMessages;
use App\Traits\ImagesHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserSugResource extends JsonResource
{
    use ImagesHelper;

    public function toArray(Request $request): array
    {
        if (!$this->phone_number && auth()->user() && auth()->user()->role_id != 3)
            $phone_number = $this->archivedAccount->phone_number;
        else {
            $phone_number = $this->phone_number ?? "";
        }

        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "avatar"        => $this->getProfileImage($this),
            "phone_number"  => $phone_number,
            "role_id"       => $this->role_id,
            "role_name"     => $this->role->name,
        ];
    }
}