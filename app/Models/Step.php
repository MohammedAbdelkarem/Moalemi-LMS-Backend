<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps_times()
    {
        return $this->hasMany(StepTime::class);
    }

    public function step_profit()
    {
        return $this->hasOne(StepProfit::class);
    }


    // /**
    //  * @return \App\Models\Step
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
