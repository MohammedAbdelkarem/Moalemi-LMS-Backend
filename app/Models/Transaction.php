<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
