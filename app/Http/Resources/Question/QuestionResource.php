<?php

namespace App\Http\Resources\Question;

use App\Enums\LevelEnum;
use Illuminate\Http\Request;
use App\Constants\MediaCollection;
use App\Http\Resources\Unit\UnitResource;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Answer\AnswerResource;
use App\Http\Resources\SubUnit\SubUnitResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'text' => $this->text,
            'hint' => $this->hint,
            'type' => $this->type,
            'unit_id' => $this->unit_id,
            'sub_unit_id' => $this->sub_unit_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'unit' => UnitResource::make($this->whenLoaded('unit')),
            'sub_unit' => SubUnitResource::make($this->whenLoaded('subUnit')),
            'answers' => AnswerResource::collection($this->whenLoaded('answers')),
            'media' => MediaResource::make($this->getFirstMedia(MediaCollection::QUESTION_COLLECTION)),
        ];

        if(auth()->user()->isStudent())
            $data['is_saved'] = is_saved($this->id, LevelEnum::QUESTION , auth()->id());

        return $data;
    }
}
