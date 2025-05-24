<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function dailysteps()
    {
        return $this->belongsTo(DailyStep::class);
    }
}
