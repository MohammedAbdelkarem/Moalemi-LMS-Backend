<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function water_times()
    {
        return $this->hasMany(WaterTime::class);
    }

    // /**
    //  * @return \App\Models\Water
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
