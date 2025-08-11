<?php

namespace App\Http\Resources\Reaction;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Users\Profile\UserSugResource;

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
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
        

        if(auth()->user()->isAdmin())
        {
            $data['status'] = $this->status;
        }
        else{
            $data['user'] = UserSugResource::make($this->whenLoaded('user'));
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
