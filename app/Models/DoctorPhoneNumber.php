<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorPhoneNumber extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * @return \App\Models\DoctorPhoneNumber
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            Resources::RES_DOCTOR_PHONE_NUMBER,
            GenderEnum::MALE,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
}
