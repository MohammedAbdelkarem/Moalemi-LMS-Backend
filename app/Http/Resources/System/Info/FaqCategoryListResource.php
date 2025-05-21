<?php

namespace App\Http\Resources\System\Info;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqCategoryListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        if ($user && $user->role_id != 3) {
            return [
                "id"                => $this->id,
                "name_en"           => $this->getTranslation('name', 'en'),
                "name_ar"           => $this->getTranslation('name', 'ar'),
                "app_key"           => $this->app,
                "updated_by_id"     => $this->update_by,
                "updated_by_name"   => $this->updater ? $this->updater->name : "",
                "created_at"        => Carbon::parse($this->created_at)->translatedFormat("Y-m-d g:i A"),
                "updated_at"        => Carbon::parse($this->updated_at)->translatedFormat("Y-m-d g:i A"),
            ];
        }
        return [
            "id"    => $this->id,
            "name"  => $this->name,
        ];
    }
}
