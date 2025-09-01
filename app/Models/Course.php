<?php

namespace App\Models;

use App\Constants\Resources;
use App\Models\Responsibility;
use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use App\Enums\PublishStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'courses';

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\Course
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            null,
            Resources::COURSE,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::COURSE_COLLECTION)->singleFile();
    }

    public function delete()
    {
        deleteFilesFromMedia($this, MediaCollection::COURSE_COLLECTION);
        return parent::delete();
    }

    // Relationships
    public function eLevel(): BelongsTo
    {
        return $this->belongsTo(ELevel::class, 'e_level_id');
    }

    public function cLevel(): BelongsTo
    {
        return $this->belongsTo(CLevel::class, 'c_level_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'course_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'course_id');
    }

    public function subUnits(): HasMany
    {
        return $this->hasMany(SubUnit::class, 'course_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    public function responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class, 'course_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('publish_status', PublishStatusEnum::PUBLISHED->value);
    }
}
