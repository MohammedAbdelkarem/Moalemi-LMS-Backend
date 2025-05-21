<?php

namespace App\Models\Administration\Log;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BanLog extends Model
{
    use HasFactory;
    protected $fillable = [
        "banned_id",
        "banned_by_id",
        "unbanned_by_id",
        "is_active",
        "reason",
        "unban_reason",
        "unban_by_id",
        "banned_until"
    ];

    public function bannedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, "banned_id");
    }

    public function banningUser(): BelongsTo
    {
        return $this->belongsTo(User::class, "banned_by_id");
    }

    public function unbanningUser(): BelongsTo
    {
        return $this->belongsTo(User::class, "unbanned_by_id");
    }
}