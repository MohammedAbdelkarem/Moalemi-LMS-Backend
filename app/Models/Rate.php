<?php

namespace App\Models;

use App\Constants\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rate extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    // /**
    //  * @return \App\Models\Rate
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

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}
