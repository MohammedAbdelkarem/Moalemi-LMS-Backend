<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StepTime extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];

    public function step()
    {
        return $this->belongsTo(Step::class);
    }



    // /**
    //  * @return \App\Models\StepTime
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
