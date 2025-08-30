<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\CLevel\CLevelResource;
use App\Http\Resources\ELevel\ELevelResource;
use App\Http\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'c_level_id' => $this->c_level_id,
            'name' => $this->name,
            'bio' => $this->bio,
            'publish_status' => $this->publish_status,
            'number_of_contents' => $this->number_of_contents,
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'number_of_lessons' => $this->number_of_lessons,
            'price' => $this->price,
            'number_of_purchased_students' => $this->number_of_purchased_students,
            'access_type' => $this->access_type,
            'number_of_teachers' => $this->number_of_teachers,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::COURSE_COLLECTION)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'c_level' => CLevelResource::make($this->whenLoaded('cLevel')),
            'subjects' => SubjectResource::collection($this->whenLoaded('subjects')),
            // 'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        return $data;
    }
}
