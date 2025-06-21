<?php

namespace App\Http\Resources\Reaction;

use App\Constants\RouteNames;
use App\Enums\ReactionStatusEnum;
use App\Enums\ReactionTypeEnum;
use App\Http\Resources\Users\Profile\UserSugResource;
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
                if($this->status == ReactionStatusEnum::EXIST->value && auth()->user()->isRegularUser())
                    $data['comment'] = $this->comment;
                    $data['is_own_comment'] = $this->user_id == auth()->id();
            }
        }

        $data['user'] = UserSugResource::make($this->whenLoaded('user'));
        // $data['user'] = ($this->user->role_id == 4)
        //     ? $this->user->name
        //     : $this->user->Doctor()->first()->clinic_name;

        if($this->type == ReactionTypeEnum::COMMENT->value)
        {
            $data['replay'] = RepalyResource::collection($this->whenLoaded('existReplays') ?? $this->whenLoaded('replaies'));
            
            if(auth()->user()->isDoctor())
            {
                $data['is_able_to_replay'] = ableToReplay($this);
            }
        }

        return $data;
    }
}
