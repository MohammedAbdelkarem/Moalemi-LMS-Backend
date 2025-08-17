<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Responsibility;
use App\Models\Quiz;
use App\Models\File;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Constants\MediaCollection;

class SubUnit extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [
        'id'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::SUB_UNIT_COLLECTION)->singleFile();
    }

    public function delete()
    {
        deleteFilesFromMedia($this, MediaCollection::SUB_UNIT_COLLECTION);
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

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'sub_unit_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'sub_unit_id');
    }

    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'context');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'context');
    }

    public function responsibilities()
    {
        return $this->morphMany(Responsibility::class, 'context');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('publish_status', 'published');
    }

    public function scopeActive($query)
    {
        return $query->where('publish_status', 'published');
    }
}
