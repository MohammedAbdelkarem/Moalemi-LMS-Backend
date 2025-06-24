<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerPatientWeightHistory extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // /**
    //  * @return \App\Models\OwnerPatientWeightHistory
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
