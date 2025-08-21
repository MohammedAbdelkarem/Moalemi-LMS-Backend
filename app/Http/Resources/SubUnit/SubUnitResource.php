<?php

namespace App\Http\Resources\SubUnit;

use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\CLevel\CLevelResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\ELevel\ELevelResource;
use App\Http\Resources\Lesson\LessonResource;
use App\Http\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubUnitResource extends JsonResource
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
            'subject_id' => $this->subject_id,
            'unit_id' => $this->unit_id,
            'name' => $this->name,
            'bio' => $this->bio,
            'publish_status' => $this->publish_status,
            'number_of_contents' => $this->number_of_contents,
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'number_of_lessons' => $this->number_of_lessons,
            'number_of_quizzes' => $this->number_of_quizzes,
            'number_of_published_quizzes' => $this->number_of_published_quizzes,
            'number_of_files' => $this->number_of_files,
            'number_of_published_files' => $this->number_of_published_files,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::SUB_UNIT_COLLECTION)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'unit' => UnitResource::make($this->whenLoaded('unit')),
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        return $data;
    }
}
