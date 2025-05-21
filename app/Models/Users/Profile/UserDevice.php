<?php

namespace App\Models\Users\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDevice extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'notification_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
