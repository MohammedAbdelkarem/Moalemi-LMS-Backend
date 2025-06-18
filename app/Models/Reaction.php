<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Enums\ReactionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replaies()
    {
        return $this->hasMany(Replay::class);
    }

    public function existReplays()
    {
        return $this->replaies()->where('status' , ReactionStatusEnum::EXIST->value);
    }

    /**
     * @return \App\Models\Reaction
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_REACTION,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }
}
