<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\ELevel\ELevelResource;
use App\Http\Resources\CLevel\CLevelResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\SubUnit\SubUnitResource;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'sub_unit_id' => $this->sub_unit_id,
            'name' => $this->name,
            'bio' => $this->bio,
            'publish_status' => $this->publish_status,
            'duration' => $this->duration,
            'priority' => $this->priority,
            'number_of_quizzes' => $this->number_of_quizzes,
            'number_of_published_quizzes' => $this->number_of_published_quizzes,
            'number_of_files' => $this->number_of_files,
            'number_of_published_files' => $this->number_of_published_files,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'media' => MediaResource::collection($this->getMedia(MediaCollection::LESSON_COLLECTION)),
            'sub_unit' => SubUnitResource::make($this->whenLoaded('subUnit')),
            'quizzes' => $this->whenLoaded('quizzes'),
            'files' => $this->whenLoaded('files'),
            'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        // Handle video with resolution support
        if ($this->hasVideo()) {
            $resolution = $request->get('resolution', '720p');
            $data['video'] = [
                'url' => $this->getVideoWithResolution($resolution),
                'resolution' => $resolution,
                'available_resolutions' => ['original', '720p', '480p', '360p', 'thumbnail'],
                'thumbnail' => $this->getVideoWithResolution('thumbnail'),
                'media_id' => $this->getVideo()->id
            ];
        }

        return $data;
    }
}
