<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
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

    public function visitInfos()
    {
        return $this->hasMany(VisitInfo::class);
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }
}