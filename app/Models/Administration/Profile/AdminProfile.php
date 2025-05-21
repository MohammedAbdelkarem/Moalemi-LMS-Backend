<?php

namespace App\Models\Administration\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'password',
        'created_by',
    ];

    protected $hidden = [
        'password',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }
}
