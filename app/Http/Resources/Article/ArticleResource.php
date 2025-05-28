<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'number_of_likes' => $this->number_of_likes,
            'number_of_comments' => $this->number_of_comments,
            'media' => MediaResource::collection($this->getMedia(MediaCollection::ARTICLE_COLLECTION)),
        ];

        $routeName = $request->route()->getName();

        // switch ($routeName)
        // {
        //     case RouteNames::EXAMPLE:
        //         $data['foo']   = $this->bar;
        //     break;
        //     case RouteNames::EXAMPLE:
        //         $data['foo']   = $this->bar;
        //     break;
        // }

        return $data;
    }
}
