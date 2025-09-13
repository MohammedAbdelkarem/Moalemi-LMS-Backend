<?php

namespace App\Http\Resources\Lesson;

use App\Enums\LevelEnum;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Video\VideoResource;
use App\Http\Resources\CLevel\CLevelResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\ELevel\ELevelResource;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\SubUnit\SubUnitResource;
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
            'duration' => $this->duration,
            'priority' => $this->priority,
            'total_rate' => $this->total_rate,
            'number_of_published_quizzes' => $this->number_of_published_quizzes,
            'number_of_published_files' => $this->number_of_published_files,
            'media' => MediaResource::collection($this->getMedia(MediaCollection::LESSON_COLLECTION)),
            // 'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        if(auth()->user()->isStudent())
        {
            $is_purchased = is_purchased($this->id, LevelEnum::LESSON , auth()->id());
            $data['is_watched'] = watched($this);
            $data['is_purchased'] = $is_purchased;
            $data['is_saved'] = is_saved($this->id, LevelEnum::LESSON , auth()->id());
            $data['video'] = $is_purchased 
                ? MediaResource::collection($this->getMedia(MediaCollection::LESSON_VIDEO_COLLECTION)) 
                : [];
        }

        $routeName = $request->route()->getName();
        switch ($routeName) 
        {
            case RouteNames::ADMIN_LESSON_LIST:
                $data['publish_status'] = $this->publish_status;
                $data['number_of_quizzes'] = $this->number_of_quizzes;
                $data['number_of_files'] = $this->number_of_files;
                $data['created_at'] = $this->created_at;
                $data['updated_at'] = $this->updated_at;
                $data['quizzes'] = $this->whenLoaded('quizzes');
                $data['files'] = $this->whenLoaded('files');
                $data['sub_unit'] = SubUnitResource::make($this->whenLoaded('subUnit'));
                $data['video'] = MediaResource::collection($this->getMedia(MediaCollection::LESSON_VIDEO_COLLECTION));
            break;
            case RouteNames::MOBILE_HIERARICHY_SUB_UNIT:
                $data['files'] = FileResource::collection($this->whenLoaded('publishedFiles'));
                $data['quizzes'] = QuizResource::collection($this->whenLoaded('publishedQuizzes'));
            break;
            case RouteNames::MOBILE_SAVED_LESSONS:
                $data['video'] = MediaResource::collection($this->getMedia(MediaCollection::LESSON_VIDEO_COLLECTION));
            break;
        }

        return $data;
    }
}
