<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaint extends Model implements HasMedia
{
    use HasFactory , InteractsWithMedia;
    
    protected $guarded = [
        'id'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::COMPLAINT_COLLECTION);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // /**
    //  * @return \App\Models\Complaint
    //  */
    // public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    // {
    //     return findByIdOrFail(
    //         self::class,
    //         $id,
    //         GenderEnum::MALE,
    //         Resources::RES_MODEL,
    //         $with,
    //         $withTrashed,
    //         $selectedColumns
    //     );
    // }
}
