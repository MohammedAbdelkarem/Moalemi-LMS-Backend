<?php

namespace App\Models;

use App\Models\Scopes\LoadUserScope;
use App\Enums\CommentStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Replay extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];


    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new LoadUserScope);
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    // Scopes
    public function scopeExist($query)
    {
        return $query->where('status', CommentStatusEnum::EXIST->value);
    }

    public function scopeDeleted($query)
    {
        return $query->where('status', CommentStatusEnum::DELETED->value);
    }
}
