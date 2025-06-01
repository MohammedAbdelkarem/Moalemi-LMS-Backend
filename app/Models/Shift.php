<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function comingReservations()
    {
        return $this->reservations()->where('status' , ReservationStatusEnum::PENDING);
    }

    /**
     * @return \App\Models\Shift
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_SHIFT,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
}