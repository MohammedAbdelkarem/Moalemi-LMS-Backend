<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function reactionable()
    {
        return $this->morphTo();
    }
}
