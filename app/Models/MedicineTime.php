<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineTime extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    // /**
    //  * @return \App\Models\model
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

    public function medicine_day()
    {
        return $this->belongsTo(MedicineDay::class);
    }
}
