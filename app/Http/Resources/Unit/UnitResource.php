<?php

namespace App\Http\Resources\Unit;

use App\Enums\LevelEnum;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\CLevel\CLevelResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\ELevel\ELevelResource;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\SubUnit\SubUnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'name' => $this->name,
            'bio' => $this->bio,
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'price' => $this->price,
            'access_type' => $this->access_type,
            'number_of_published_quizzes' => $this->number_of_published_quizzes,
            'number_of_published_files' => $this->number_of_published_files,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::UNIT_COLLECTION)),
        ];

        if(auth()->user()->isStudent())
            $data['is_purchased'] = is_purchased($this->id, LevelEnum::UNIT , auth()->id());


        $routeName = $request->route()->getName();
        switch ($routeName) 
        {
            case RouteNames::ADMIN_UNIT_LIST:
                $data['created_at'] = $this->created_at;
                $data['updated_at'] = $this->updated_at;
                $data['publish_status'] = $this->publish_status;
                $data['number_of_contents'] = $this->number_of_contents;
                $data['number_of_quizzes'] = $this->number_of_quizzes;
                $data['number_of_files'] = $this->number_of_files;
                $data['subject'] = SubjectResource::make($this->whenLoaded('subject'));
                $data['sub_units'] = SubUnitResource::collection($this->whenLoaded('subUnits'));
            break;
            case RouteNames::MOBILE_HIERARICHY_UNIT:
                $data['sub_units'] = SubUnitResource::collection($this->whenLoaded('publishedSubUnits'));
            break;
            case RouteNames::MOBILE_HIERARICHY_UNIT_DETAILS:
                $data['teacher'] = UserResource::make($this->whenLoaded('responsibilities')->pluck('teacher')->first());
                $data['files'] = FileResource::collection($this->whenLoaded('publishedFiles'));
                $data['quizzes'] = QuizResource::collection($this->whenLoaded('publishedQuizzes'));
            break;
        }

        return $data;
    }
}
