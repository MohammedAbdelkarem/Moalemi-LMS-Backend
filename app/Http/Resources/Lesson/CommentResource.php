<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'text' => $this->text,
            'is_pinned' => $this->is_pinned,
            'created_at' => $this->created_at,
            'user' => UserResource::make($this->whenLoaded('user')),
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::MOBILE_COMMENTS_LIST:
                $data['replays'] = ReplayResource::collection($this->whenLoaded('existReplays'));
                break;
        }

        return $data;
    }
}
