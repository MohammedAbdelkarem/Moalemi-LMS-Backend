<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;
    protected $guarded = ['id'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::CATEGORY_COLLECTION);
    }

    /**
     * @return \App\Models\Category
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_CATEGORY,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function delete()
    {
        deleteFilesFromMedia($this , MediaCollection::CATEGORY_COLLECTION);

        return parent::delete();
    }
}
