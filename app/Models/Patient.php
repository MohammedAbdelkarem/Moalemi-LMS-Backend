<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function treatments()
    {
        return $this->hasMany(Treatment::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Doctor::class)
            ->using(Favorite::class)
            ->withTimestamps();
    }
    
}