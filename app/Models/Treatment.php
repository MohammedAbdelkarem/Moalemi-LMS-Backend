<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    
    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }

    public function treatmentTimes()
    {
        return $this->hasMany(TreatmentTime::class);
    }

    
}
