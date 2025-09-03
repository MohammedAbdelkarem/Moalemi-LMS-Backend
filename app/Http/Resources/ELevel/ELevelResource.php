<?php

namespace App\Http\Resources\ELevel;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
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
            'number_of_published_contents' => $this->number_of_published_contents,
            'duration' => $this->duration,
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::E_LEVEL_COLLECTION)),
            // 'responsibilities' => $this->whenLoaded('responsibilities'),
        ];

        $routeName = $request->route()->getName();
        switch ($routeName) 
        {
            case RouteNames::ADMIN_E_LEVEL_LIST:
                $data['publish_status'] = $this->publish_status;
                $data['number_of_contents'] = $this->number_of_contents;
                $data['created_at'] = $this->created_at;
                $data['updated_at'] = $this->updated_at;
                $data['c_levels'] = CLevelResource::collection($this->whenLoaded('cLevels'));
            break;
        }

        return $data;
    }
} 