<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];



    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function context()
    {
        return $this->morphTo();
    }

    public function replays(): HasMany
    {
        return $this->hasMany(Replay::class, 'comment_id');
    }

    // Scopes
    public function scopeExist($query)
    {
        return $query->where('status', 'exist');
    }

    public function scopeDeleted($query)
    {
        return $query->where('status', 'deleted');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}
