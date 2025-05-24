<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SubCategory extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;
    protected $guarded = ['id'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'specializations')
                    ->using(Specialization::class)
                    ->withTimestamps();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::SUB_CATEGORY_COLLECTION);
    }

    /**
     * @return \App\Models\SubCategory
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::FEMALE,
            Resources::RES_SUBCATEGORY,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function delete()
    {
        deleteFilesFromMedia($this , MediaCollection::SUB_CATEGORY_COLLECTION);

        return parent::delete();
    }

    public function scopefilter($query ,$data)
    {

        return $query->when(isset($data['category_ids']) , function ($query) use ($data) {
            $query->whereIn('category_id' , $data['category_ids']);
        });
    }
}
