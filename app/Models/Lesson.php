<?php

namespace App\Models;

use App\Models\File;
use App\Models\Quiz;
use App\Constants\Resources;
use App\Models\Responsibility;
use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Lesson extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\Lesson
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            null,
            Resources::LESSON,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
    public function registerMediaCollections(): void
    {
        // Multiple images collection (no limit, can store multiple images)
        $this->addMediaCollection(MediaCollection::LESSON_COLLECTION)
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
            ->useDisk('media');

        // Single video collection with resolution variants
        $this->addMediaCollection(MediaCollection::LESSON_VIDEO_COLLECTION)
            ->singleFile()
            ->acceptsMimeTypes(['video/mp4', 'video/webm', 'video/mov', 'video/avi'])
            ->useDisk('media')
            ->registerMediaConversions(function (Media $media) {
                // 720p variant
                $this->addMediaConversion('720p')
                    ->width(1280)
                    ->height(720)
                    ->quality(80);

                // 480p variant
                $this->addMediaConversion('480p')
                    ->width(854)
                    ->height(480)
                    ->quality(70);

                // 360p variant
                $this->addMediaConversion('360p')
                    ->width(640)
                    ->height(360)
                    ->quality(60);

                // Thumbnail for video preview
                $this->addMediaConversion('thumbnail')
                    ->width(320)
                    ->height(180)
                    ->quality(80)
                    ->extractVideoFrameAtSecond(1);
            });
    }

    public function delete()
    {
        // Delete all media files (images and video)
        deleteFilesFromMedia($this, MediaCollection::LESSON_COLLECTION);
        deleteFilesFromMedia($this, MediaCollection::LESSON_VIDEO_COLLECTION);
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

    public function subUnit(): BelongsTo
    {
        return $this->belongsTo(SubUnit::class, 'sub_unit_id');
    }

    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'context');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'context');
    }

    public function lessonQuestions(): HasMany
    {
        return $this->hasMany(LessonQuestion::class, 'lesson_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'context');
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

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    // Media Helper Methods
    public function getImages()
    {
        return $this->getMedia(MediaCollection::LESSON_COLLECTION);
    }

    public function getVideo()
    {
        return $this->getFirstMedia(MediaCollection::LESSON_VIDEO_COLLECTION);
    }

    public function getVideoWithResolution($resolution = '720p')
    {
        $video = $this->getVideo();
        if (!$video) return null;

        switch($resolution) {
            case '720p':
                return $video->getUrl('720p');
            case '480p':
                return $video->getUrl('480p');
            case '360p':
                return $video->getUrl('360p');
            case 'thumbnail':
                return $video->getUrl('thumbnail');
            case 'original':
                return $video->getUrl();
            default:
                return $video->getUrl('720p');
        }
    }

    public function hasVideo()
    {
        return $this->hasMedia(MediaCollection::LESSON_VIDEO_COLLECTION);
    }

    public function hasImages()
    {
        return $this->hasMedia(MediaCollection::LESSON_COLLECTION);
    }
}
