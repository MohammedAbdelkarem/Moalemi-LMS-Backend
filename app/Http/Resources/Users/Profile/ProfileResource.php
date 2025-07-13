<?php

namespace App\Http\Resources\Users\Profile;

use Carbon\Carbon;
use App\Models\Patient;
use App\Traits\ImagesHelper;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorResouce;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    use ImagesHelper;
    public function toArray(Request $request): array
    {
        if (!$this->phone_number && auth()->user() )
            $phone_number = $this->archivedAccount->phone_number;
        else {
            $phone_number = $this->phone_number ?? "";
        }


        // dd(auth()->id() , $this->id);
        $data = [
            "is_me"     => $this->id == auth()->id(),
            "has_medical_profile"  => Patient::where('user_id' , auth()->id())->where('is_owner' , 1)->exists(),
            "id"        => $this->id,
            "name"      => $this->name,
            "avatar"        => $this->getProfileImage($this),
            "ban"           => (auth()->id() == $this->id ) ? $this->getBanData() : null,
            "birth_date"    => $this->birth_date ?? "",
            "is_male"       => !is_null($this->is_male) ? (bool) $this->is_male : null,
            "email"         => $this->email ?? "",
            "phone_number"  => $phone_number,
            "role_id"  => $this->role_id,
            "city_id"       => $this->city_id,
            "city_name"     => $this->city["name_" . app()->getLocale()] ?? "",
            "created_at"           => Carbon::parse($this->created_at)->translatedFormat("Y-m-d g:i a"),
            "active_notifications" => (bool) $this->active_notifications,
        ];

        if($this->role_id == 4)
        {
            $data['owner_patient_id'] = owner_id();
        } 

        if (auth()->user() ) {
            $data += $this->getAdminData();
        }

        if($this->role_id == 3)
        {
            // dd($this->whenLoaded('Doctor'));
            $data['doctor'] = DoctorResouce::make($this->whenLoaded('Doctor'));
        }

        return $data;
    }

    public function getAdminData()
    {
        return [
            "phone_number"  => $this->phone_number ?? $this->archivedAccount->phone_number,
            "in_trash"      => (bool) $this->deleted_at,
            "deleted_at"    => $this->deleted_at ?? "",
            "is_active"     => (bool) !$this->deactive_at,
            "deactive_at"   => $this->deactive_at ?? "",
        ];
    }

    public function getBanData()
    {
        $isBanned = (bool)($this->profile->banned_until && Carbon::parse($this->profile->banned_until)->gt(Carbon::now()));

        return [
            "is_banned"     => $isBanned,
            "banned_until"  => $isBanned
                ? Carbon::parse($this->profile->banned_until)->translatedFormat("y-m-d g:i a")
                : "",
            "ban_reason"    => ($isBanned && $this->bans) ?  $this->bans[0]["reason"] : "",
        ];
    }
}
