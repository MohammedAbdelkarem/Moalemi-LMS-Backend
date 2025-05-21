<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPhoneNumber extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
