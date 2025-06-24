<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Enums\TreatmentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * @return \App\Models\Patient
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_PATIENT,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function weight_history()
    {
        return $this->hasMany(OwnerPatientWeightHistory::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
    
    public function instructions()
    {
        return $this->hasMany(Instruction::class);
    }
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    public function addedMedicines()
    {
        return $this->morphMany(Medicine::class, 'userable');
    }

    
    public function addedInstructions()
    {
        return $this->morphMany(Instruction::class, 'userable');
    }
    public function permanent_instructions()
    {
        return $this->instructions()->where('status' , TreatmentStatusEnum::PERMANENT->value);
    }
    public function permanent_medicines()
    {
        return $this->medicines()->where('status' , TreatmentStatusEnum::PERMANENT->value);
    }

    public function favoriteDoctors()
    {
        return $this->favorites()->where('favoritable_type', Doctor::class);
    }

    public function favoriteArticles()
    {
        return $this->favorites()->where('favoritable_type', Article::class);
    }


}