<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Constants\MediaCollection;
use App\Enums\ReactionStatusEnum;
use App\Enums\ReactionTypeEnum;
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

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function comments()
    {
        return $this->reactions()->where('type' , ReactionTypeEnum::COMMENT->value);
    }

    public function existsReactions()
    {
        return $this->reactions()->where('status' , ReactionStatusEnum::EXIST->value);
    }
    public function existsComments()
    {
        return $this->existsReactions()->where('type' , ReactionTypeEnum::COMMENT->value);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function ArticleViews()
    {
        return $this->hasMany(ArticleView::class);
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

    public function scopeFilter($query , $data)
    {
        return $query 

        ->when(isset($data['text']) , function($query) use ($data) {
            $query->where('title' , 'like' , '%' . $data['text'] . '%')
                    ->orWhere('body' , 'like' , '%' . $data['text'] . '%');
        })
        ->when(isset($data['fav']) , function($query) use ($data) {
            return $query->whereHas('favorites' , function($query) use ($data) {
                return $query->where('user_id' , auth()->id());
            });
        })
        ->when(isset($data['category_ids']) , function($query) use ($data) {
            return $query->whereHas('doctor' , function($query) use ($data) {
                return $query->whereHas('subCategories' , function($query) use ($data) {
                    return $query->whereIn('category_id' , $data['category_ids']);
                });
            });
        });
    }
}
