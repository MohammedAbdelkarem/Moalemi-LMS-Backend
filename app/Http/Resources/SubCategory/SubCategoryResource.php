<?php

namespace App\Http\Resources\SubCategory;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
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
            'bio'  => $this->bio,
            'media' => MediaResource::collection($this->getMedia(MediaCollection::SUB_CATEGORY_COLLECTION)),
            'category_id' => $this->category_id,
            // 'category' => $this-
        ];

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::DOCTORS_GET_PROFILE:
                $data['category']   = $this->whenLoaded('category');
            break;
        }

        return $data;
    }
}
