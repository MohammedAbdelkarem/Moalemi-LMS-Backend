<?php

namespace App\Models\Users\Profile;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivedUser extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'phone_number'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
