<?php

namespace App\Http\Resources\Administration\Profile;

use App\Traits\ImagesHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminListResource extends JsonResource
{
    use ImagesHelper;
    public function toArray(Request $request): array
    {
        return [
            "id"        => $this->id,
            "name"      => $this->name,
            "role_name" => $this->role->name,
            "avatar"    => $this->getProfileImage($this),
            "email"         => $this->email,
            "is_active"     => (bool) !$this->deactive_at,
            "created_at"    => Carbon::parse($this->created_at)->translatedFormat("Y-m-d g:i a"),
        ];
    }
}
