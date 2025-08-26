<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Request;
use App\Http\Resources\Question\QuestionResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'context_id' => $this->context_id,
            'context_type' => getmodelname($this->context_type),
            'period' => $this->period,
            'degree' => $this->degree,
            'pass_degree' => $this->pass_degree,
            'number_of_questions' => $this->number_of_questions,
            'priority' => $this->priority,
            'publish_status' => $this->publish_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => UserResource::make($this->whenLoaded('createdBy')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
