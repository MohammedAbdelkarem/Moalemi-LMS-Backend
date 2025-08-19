<?php

namespace App\Http\Resources\Subject;

use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\CLevel\CLevelResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\ELevel\ELevelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
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
            'course_id' => $this->course_id,
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
            'number_of_quizzes' => $this->number_of_quizzes,
            'number_of_files' => $this->number_of_files,
            'number_of_published_quizzes' => $this->number_of_published_quizzes,
            'number_of_published_files' => $this->number_of_published_files,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::SUBJECT_COLLECTION)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'course' => CourseResource::make($this->whenLoaded('course')),
            'units' => UnitResource::collection($this->whenLoaded('units')),
            'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        return $data;
    }
}
