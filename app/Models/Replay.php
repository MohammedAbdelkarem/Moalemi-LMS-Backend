<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Replay extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    public function reaction()
    {
        return $this->belongsTo(Reaction::class);
    }

    /**
     * @return \App\Models\Replay
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_REACTION,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }


}
