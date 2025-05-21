<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitInfo extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'visit_id', 'type'];

    public function visit()
    {
        return $this->belongsTo(Visit::class);
    }
}