<?php

namespace App\Http\Resources\Context;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UnlockedContextResource extends JsonResource
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
            'context_id' => $this->context_id,
            'context_type' => getModelName($this->context_type),
            'context' => ($this->context_type == Course::class) ? CourseResource::make($this->whenLoaded('context')) : SubjectResource::make($this->whenLoaded('context')),
        ];

        $routeName = $request->route()->getName();


        return $data;
    }
}
