<?php

namespace App\Http\Resources\CLevel;

use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\ELevel\ELevelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CLevelResource extends JsonResource
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
            'e_level_id' => $this->e_level_id,
            'name' => $this->name,
            'bio' => $this->bio,
            'publish_status' => $this->publish_status,
            'number_of_contents' => $this->number_of_contents,
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'number_of_lessons' => $this->number_of_lessons,
            'number_of_students' => $this->number_of_students,
            'number_of_teachers' => $this->number_of_teachers,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::C_LEVEL_COLLECTION)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'e_level' => ELevelResource::make($this->whenLoaded('eLevel')),
            'courses' => CourseResource::collection($this->whenLoaded('courses')),
            // 'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        return $data;
    }
}
