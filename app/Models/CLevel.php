<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Models\Responsibility;
use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CLevel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'c_levels';

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\CLevel
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::C_LEVEL,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::C_LEVEL_COLLECTION)->singleFile();
    }

    public function delete()
    {
        deleteFilesFromMedia($this, MediaCollection::C_LEVEL_COLLECTION);
        return parent::delete();
    }

    // Relationships
    public function eLevel(): BelongsTo
    {
        return $this->belongsTo(ELevel::class, 'e_level_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'c_level_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'c_level_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'c_level_id');
    }

    public function subUnits(): HasMany
    {
        return $this->hasMany(SubUnit::class, 'c_level_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'c_level_id');
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
}
