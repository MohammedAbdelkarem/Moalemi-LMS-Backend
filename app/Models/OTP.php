<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OTP extends Model
{
    protected $table = "otps";

    use HasFactory;
    protected $fillable = ["user_id", "otp", "expire_at"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
