<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentHistory extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    
    public function itemable()
    {
        return $this->morphTo();
    }

    // /**
    //  * @return \App\Models\TreatmentHistory
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

    // Mutator to serialize history ids before saving to the database
    public function setHistoryIdsAttribute($value)
    {
        $this->attributes['history_ids'] = json_encode($value);
    }

    // Accessor to deserialize the history ids array when retrieving from the database
    public function getHistoryIdsAttribute($value)
    {
        return json_decode($value, true);
    }
}
