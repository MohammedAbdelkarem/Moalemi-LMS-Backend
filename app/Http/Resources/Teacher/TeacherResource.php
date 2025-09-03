<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Responsibility\ResponsibilityResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => MediaResource::make($this->getFirstMedia(MediaCollection::USER_COLLECTION)),
            'role_id' => $this->role_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        $routeName = $request->route()->getName();

        switch ($routeName) 
        {
            case RouteNames::ADMIN_TEACHER_LIST:
                $data['bio'] = $this->bio;
                $data['responsibilities'] = ResponsibilityResource::collection($this->whenLoaded('responsibilities'));
            break;
        }

        return $data;
    }
}
