<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopeDoctorId($query)
    {
        return $query->where('doctor_id' , doctor_id());
    }

    public function scopeFilter($query , $data)
    {
        return $query

        ->when(isset($data['start_date']) , function($query) use ($data) {
            $query->where('created_at' , '>=' , $data['start_date']);
        })

        ->when(isset($data['end_date']) , function($query) use ($data) {
            $query->where('created_at' , '<=' , $data['end_date']);
        })

        ->when(isset($data['doctor_ids']) , function($query) use ($data) {
            $query->whereIn('doctor_id' , $data['doctor_ids']);
        });
    }
}
