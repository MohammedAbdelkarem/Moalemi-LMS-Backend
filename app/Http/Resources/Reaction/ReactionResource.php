<?php

namespace App\Http\Resources\Reaction;

use App\Constants\RouteNames;
use App\Enums\ReactionStatusEnum;
use App\Enums\ReactionTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
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
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];

        if($this->type == ReactionTypeEnum::COMMENT->value)
        {
            if(auth()->user()->isAdmin())
            {
                $data['comment'] = $this->comment;
                $data['status'] = $this->status;
            }
            else
            {
                if($this->status == ReactionStatusEnum::EXIST->value)
                    $data['comment'] = $this->comment;
            }
        }

        $data['user'] = ($this->user->role_id == 4)
            ? $this->user->name
            : $this->user->Doctor()->first()->clinic_name;

        return $data;
    }
}
