<?php

namespace App\Models\Users\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NumberUpdate extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "phone_number",
        "otp",
        "expire_at",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
