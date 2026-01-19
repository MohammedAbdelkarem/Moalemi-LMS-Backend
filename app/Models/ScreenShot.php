<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class ScreenShot extends Model
{
    use HasFactory;
    
    protected $guarded = [
        'id'
    ];


    /**
     * @return \App\Models\ScreenShot
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::RES_SCREEN_SHOT,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeNotDisabled($query): Builder
    {
        return $query->where('is_disabled', 0);
    }
}
