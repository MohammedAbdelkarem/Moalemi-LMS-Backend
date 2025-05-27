<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;
    protected $guarded = ['id'];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::ARTICLE_COLLECTION);
    }

    /**
     * @return \App\Models\Article
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::FEMALE,
            Resources::RES_ARTICLE,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function delete()
    {
        deleteFilesFromMedia($this , MediaCollection::ARTICLE_COLLECTION);

        return parent::delete();
    }
}
