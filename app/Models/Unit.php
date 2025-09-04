<?php

namespace App\Models;

use App\Constants\Resources;
use App\Models\Responsibility;
use App\Enums\PublishStatusEnum;
use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'units';

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\Unit
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            null,
            Resources::UNIT,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::UNIT_COLLECTION)->singleFile();
    }

    public function delete()
    {
        deleteFilesFromMedia($this, MediaCollection::UNIT_COLLECTION);
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

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function subUnits(): HasMany
    {
        return $this->hasMany(SubUnit::class, 'unit_id');
    }

    public function publishedSubUnits(): HasMany
    {
        return $this->subUnits()->published();
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'unit_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'context');
    }

    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'context');
    }

    public function responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class, 'unit_id');
    }

    public function unlockedContexts(): MorphMany
    {
        return $this->morphMany(UnlockedContext::class, 'context');
    }

    public function coupons(): MorphMany
    {
        return $this->morphMany(Coupon::class, 'context');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('publish_status', PublishStatusEnum::PUBLISHED->value);
    }

    public function numberOfLessons()
    {
        return $this->lessons()->count();
    }
    public function numberOfPublishedLessons()
    {
        return $this->lessons()->published()->count();
    }

    public function publishedFiles(): MorphMany
    {
        return $this->files()->published();
    }

    public function publishedQuizzes(): MorphMany
    {
        return $this->quizzes()->published();
    }
}
