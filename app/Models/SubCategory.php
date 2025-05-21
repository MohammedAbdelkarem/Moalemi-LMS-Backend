<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'subscriptions')
                    ->using(Specialization::class)
                    ->withTimestamps();
    }
}
