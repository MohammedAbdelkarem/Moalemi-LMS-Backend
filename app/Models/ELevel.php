<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Responsibility;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Constants\MediaCollection;

class ELevel extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'e_levels';

    protected $guarded = [
        'id'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::E_LEVEL_COLLECTION)->singleFile();
    }

    public function delete()
    {
        deleteFilesFromMedia($this, MediaCollection::E_LEVEL_COLLECTION);
        return parent::delete();
    }

    // Relationships
    public function cLevels(): HasMany
    {
        return $this->hasMany(CLevel::class, 'e_level_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'e_level_id');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'e_level_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'e_level_id');
    }

    public function subUnits(): HasMany
    {
        return $this->hasMany(SubUnit::class, 'e_level_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'e_level_id');
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
