<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
