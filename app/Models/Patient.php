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

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
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