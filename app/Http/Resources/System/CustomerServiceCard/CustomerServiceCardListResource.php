<?php

namespace App\Http\Resources\System\CustomerServiceCard;

use App\Http\Resources\Users\Profile\UserSugResource;
use App\Traits\ImagesHelper;
use App\Traits\StringHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerServiceCardListResource extends JsonResource
{
    use StringHelper, ImagesHelper;
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        $data = $this->getCardData();

        if ($user && $user->role_id != 3)
            $data += $this->getCardAdminData();

        return $data;
    }

    public function getCardData()
    {
        $description = $this->description;
        $description = $this->getShorterString($this->description, length: 203);

        return [
            "id"                => $this->id,
            "title"             => $this->title,
            "description"       => $description,
            "type"              => __("customer_card.{$this->type}"),
            "status"            => __("customer_card.{$this->status}"), //This for showing in UI
            "status_type"       => $this->status, //This is used for colors and graphics in frontEnd
            "messages_count"    => $this->messages_count,
            "created_at"        => Carbon::parse($this->created_at)->translatedFormat('Y-m-d g:i A'),
        ];
    }

    public function getCardAdminData()
    {
        return [
            "in_trash"          => (bool) $this->deleted_at,
            "user"              => new UserSugResource($this->user),
        ];
    }
}
