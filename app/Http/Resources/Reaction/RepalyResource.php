<?php

namespace App\Http\Resources\Reaction;

use App\Constants\RouteNames;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepalyResource extends JsonResource
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
            'replay' => $this->comment,
        ];

        if(auth()->user()->isAdmin())
        {
            $data['status'] = $this->status;
        }

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
        //     case RouteNames::EXAMPLE:
        //         $data['foo']   = $this->bar;
        //     break;
        //     case RouteNames::EXAMPLE:
        //         $data['foo']   = $this->bar;
        //     break;
        }

        return $data;
    }
}
