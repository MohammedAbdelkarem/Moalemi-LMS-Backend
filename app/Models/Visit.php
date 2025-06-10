<?php

namespace App\Models;

use App\Constants\MediaCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollection::VISIT_COLLECTION);
    }
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patientUpdatedInfo()
    {
        return $this->hasOne(PatientUpdatedInfo::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    
    public function rate()
    {
        return $this->hasOne(Rate::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function instructions()
    {
        return $this->hasMany(Instruction::class);
    }
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}