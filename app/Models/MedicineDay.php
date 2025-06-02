<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineDay extends Model
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

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function medicine_times()
    {
        return $this->hasMany(MedicineTime::class);
    }
}
