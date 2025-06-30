<?php

namespace App\Http\Resources\Users\Profile;

use App\Constants\ApiMessages;
use App\Http\Resources\DoctorResouce;
use App\Models\Doctor;
use App\Models\Patient;
use App\Traits\ImagesHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Comment\Doc;

class UserSugResource extends JsonResource
{
    use ImagesHelper;

    public function toArray(Request $request): array
    {
        if (!$this->phone_number && auth()->user() && auth()->user()->isAdmin())
            $phone_number = $this->archivedAccount->phone_number;
        else {
            $phone_number = $this->phone_number ?? "";
        }
        $patient_owner = Patient::where('user_id' , $this->id)->where('is_owner' , 1)->first();

        if($this->role_id == 3)
        {
            $data['doctor'] = DoctorResouce::make($this->whenLoaded('doctor'));
        }
        return [
            "id"            => $this->id,
            "name"          => $this->name,
            "avatar"        => $this->getProfileImage($this) ? $this->getProfileImage($patient_owner) : "",
            "phone_number"  => $phone_number,
            "role_id"       => $this->role_id,
            "role_name"     => $this->role->name,
        ];
    }
}