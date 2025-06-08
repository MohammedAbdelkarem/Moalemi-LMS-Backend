<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientUpdatedInfo extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }


    // /**
    //  * @return \App\Models\PatientUpdatedInfo
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
