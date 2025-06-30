<?php

namespace App\Http\Resources;

use App\Traits\ImagesHelper;
use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Traits\StorageHelper;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AddedByResource extends JsonResource
{
    use ImagesHelper;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
        ];
        
        $data['full_name'] = $this->full_name ?? $this->clinic_name;
        $data['avatar'] = $this->getProfileImage($this);
        $data['role_name'] = ($this->clinic_name != null) ? 'Doctor' : 'Patient'; 

        if($this->clinic_name != null)
        {
            $data['logo'] = MediaResource::make($this->getFirstMedia(MediaCollection::DOCTOR_LOGO_COLLECTION));
        }
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
