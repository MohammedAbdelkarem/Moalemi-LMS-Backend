<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicine extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    /**
     * @return \App\Models\Medicine
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_MEDICINE,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicine_days()
    {
        return $this->hasMany(MedicineDay::class);
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function historyIds()
    {
        return $this->morphMany(TreatmentHistory::class , 'itemable');
    }

    public function scopeAddeddByPatient($query , $patient_id)
    {
        return $query->where('patient_id' , $patient_id)->where('visit_id' , null);
    }
}
