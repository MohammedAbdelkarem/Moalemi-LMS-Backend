<?php

namespace App\Http\Resources\System\CustomerServiceCard;

use App\Constants\ApiMessages;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerServiceMessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $userInfo = $this->getUserInfo();

        $user_id    = $userInfo["user_id"];
        $user_name  = $userInfo["user_name"];

        return [
            "id"            => $this->id,
            "user_id"       => $user_id,
            "user_name"     => $user_name,
            "from_admin"    => (bool)($this->user->role_id != 3),
            "message"       => $this->message,
            "created_at"    => Carbon::parse($this->created_at)->translatedFormat('Y-m-d g:i a'),
        ];
    }

    public function getUserInfo()
    {
        $user        = auth()->user();
        $messageUser = $this->user;

        $user_id     = $messageUser->id;
        $user_name   = $messageUser->name;

        //To NOT show admin profile for normal users
        if ($user->role_id == 3 && $messageUser->role_id != 3) {
            $user_id = null;
            $user_name =  __(ApiMessages::MSG_ADMIN_ACCOUNT);
        }

        return [
            "user_id" => $user_id,
            "user_name" => $user_name
        ];
    }
}
