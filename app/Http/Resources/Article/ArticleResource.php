<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Enums\ReactionTypeEnum;
use App\Http\Resources\DoctorResouce;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Reaction\ReactionResource;
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
            'views' => $this->views,
            'media' => MediaResource::collection($this->getMedia(MediaCollection::ARTICLE_COLLECTION)),
            'created_at'=> $this->created_at,
            'doctor' => DoctorResouce::make($this->whenLoaded('doctor')),
        ];

        if(auth()->user()->isPatient())
        {
            $data['is_favorite'] = $this->favorites()->where('user_id' , auth()->id())->exists();
            $data['liked'] = $this->reactions()->where('user_id' , auth()->id())->where('type' , ReactionTypeEnum::LIKE->value)->exists();
        }
        $data['able_to_comment'] = ableToComment($this);

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::ARTICLES_SHOW:
                $data['comments']   = ReactionResource::collection($this->whenLoaded('existsComments') ?? $this->whenLoaded('comments'));
            break;
        }

        return $data;
    }
}
