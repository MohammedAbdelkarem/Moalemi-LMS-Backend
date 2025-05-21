<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function phoneNumbers()
    {
        return $this->hasMany(DoctorPhoneNumber::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'subscriptions')
                    ->using(Subscription::class)
                    ->withPivot(
                        'price',
                         'discount_percentage',
                         'start_at' ,
                         'end_at',
                         'number_of_days',
                         'number_of_remaining_days',
                         'is_active'
                    )
                    ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'subscriptions')
                    ->using(Specialization::class)
                    ->withTimestamps();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Patient::class)
            ->using(Favorite::class)
            ->withTimestamps();
    }
}
