<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Enums\LevelEnum;
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
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'price' => $this->price,
            'access_type' => $this->access_type,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::COURSE_COLLECTION)),
            // 'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        if(auth()->user()->isStudent())
            $data['is_purchased'] = is_purchased($this->id, LevelEnum::COURSE , auth()->id());

        $routeName = $request->route()->getName();

        switch ($routeName) 
        {
            case RouteNames::ADMIN_COURSE_LIST:
                $data['publish_status'] = $this->publish_status;
                $data['number_of_contents'] = $this->number_of_contents;
                $data['number_of_purchased_students'] = $this->number_of_purchased_students;
                $data['created_at'] = $this->created_at;
                $data['updated_at'] = $this->updated_at;
                $data['c_level'] = CLevelResource::make($this->whenLoaded('cLevel'));
                $data['subjects'] = SubjectResource::collection($this->whenLoaded('subjects'));
            break;
        }

        return $data;
    }
}
