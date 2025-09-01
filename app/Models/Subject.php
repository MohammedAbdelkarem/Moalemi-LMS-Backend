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
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'subjects';

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\Subject
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            null,
            Resources::SUBJECT,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::SUBJECT_COLLECTION)->singleFile();
    }

    public function delete()
    {
        deleteFilesFromMedia($this, MediaCollection::SUBJECT_COLLECTION);
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

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'subject_id');
    }

    public function subUnits(): HasMany
    {
        return $this->hasMany(SubUnit::class, 'subject_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'subject_id');
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
        return $this->hasMany(Responsibility::class, 'subject_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('publish_status', PublishStatusEnum::PUBLISHED->value);
    }
}
