<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Story extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia , HasTranslations;

    protected $guarded = [
        'id'
    ];

    public $translatable = [
        'title',
        'description'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::STORY_COLLECTION);
    }
    
    public function storiable()
    {
        return $this->morphTo();
    }

    /**
     * @return \App\Models\Story
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            Resources::RES_STORY,
            GenderEnum::FEMALE,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
}
