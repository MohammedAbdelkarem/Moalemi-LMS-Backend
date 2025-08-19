<?php

namespace App\Http\Resources\ELevel;

use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\CLevel\CLevelResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ELevelResource extends JsonResource
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
            'name' => $this->name,
            'bio' => $this->bio,
            'publish_status' => $this->publish_status,
            'number_of_contents' => $this->number_of_contents,
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'number_of_lessons' => $this->number_of_lessons,
            'number_of_students' => $this->number_of_students,
            'number_of_teachers' => $this->number_of_teachers,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::E_LEVEL_COLLECTION)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'c_levels' => CLevelResource::collection($this->whenLoaded('cLevels')),
            'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        return $data;
    }
} 