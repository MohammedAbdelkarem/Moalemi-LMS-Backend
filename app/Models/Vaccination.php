<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    // /**
    //  * @return \App\Models\Vaccination
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

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_vaccinations')
                    ->using(PatientVaccination::class)
                    ->withPivot(
                    'checked',
                        'note',
                    )
                    ->withTimestamps();
    }


}
