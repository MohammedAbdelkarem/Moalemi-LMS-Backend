<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'subscriptions')
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

}
