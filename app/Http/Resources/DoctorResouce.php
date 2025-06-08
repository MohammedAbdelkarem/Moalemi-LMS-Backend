<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Constants\RouteNames;
use App\Constants\MediaCollection;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResouce extends JsonResource
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
            'clinic_name' => $this->clinic_name,
            'address_text' => $this->address_text,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'license_number' => $this->license_number,
            'is_center' => $this->is_center,
            'bio' => $this->bio,
            'rate' => $this->total_rate,
            'licenses' => MediaResource::collection($this->getMedia(MediaCollection::DOCTOR_CERTIFICATES_COLLECTION)),
            'cover' => MediaResource::collection($this->getMedia(MediaCollection::DOCTOR_COVER_COLLECTION)),
            'logo' => MediaResource::collection($this->getMedia(MediaCollection::DOCTOR_LOGO_COLLECTION)),
        ];

        if(auth()->user()->isPatient())
        {
            $data['is_favorite'] = $this->favorites()->where('user_id' , auth()->id())->exists();
        }

        $routeName = $request->route()->getName();

        switch ($routeName)
        {
            case RouteNames::DOCTORS_FILTER_USER_SIDE:
                $data['sub_categories']   = $this->whenLoaded('subCategories');
                $data['shifts']   = ShiftResource::collection($this->whenLoaded('shifts'));
            break;
            case RouteNames::DOCTORS_GET_PROFILE:
                $data['sub_categories']   = $this->whenLoaded('subCategories');
                $data['shifts']   = ShiftResource::collection($this->whenLoaded('shifts'));
                $data['rates']   = $this->whenLoaded('rates');
                $data['articles']   = $this->whenLoaded('articles');
            break;
        }

        return $data;
    }
}
