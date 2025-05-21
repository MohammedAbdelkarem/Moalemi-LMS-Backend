<?php

namespace App\Http\Resources\Users\Profile;

use App\Traits\ImagesHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    use ImagesHelper;
    public function toArray(Request $request): array
    {
        if (!$this->phone_number && auth()->user() && auth()->user()->role_id != 3)
            $phone_number = $this->archivedAccount->phone_number;
        else {
            $phone_number = $this->phone_number ?? "";
        }

        $data = [
            "is_me"     => $this->id == auth()->id(),
            "id"        => $this->id,
            "name"      => $this->name,
            "avatar"        => $this->getProfileImage($this),
            "ban"           => (auth()->id() == $this->id || auth()->user()->role_id != 3) ? $this->getBanData() : null,
            "birth_date"    => $this->birth_date ?? "",
            "is_male"       => !is_null($this->is_male) ? (bool) $this->is_male : null,
            "email"         => $this->email ?? "",
            "phone_number"  => $phone_number,
            "city_id"       => $this->city_id,
            "city_name"     => $this->city["name_" . app()->getLocale()] ?? "",
            "created_at"           => Carbon::parse($this->created_at)->translatedFormat("Y-m-d g:i a"),
            "active_notifications" => (bool) $this->active_notifications,
        ];

        if (auth()->user() && auth()->user()->role_id != 3) {
            $data += $this->getAdminData();
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
