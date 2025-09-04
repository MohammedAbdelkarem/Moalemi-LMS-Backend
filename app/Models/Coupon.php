<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Enums\CouponTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    /**
     * @return \App\Models\Coupon
     */
    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::MALE,
            Resources::COUPON,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

    // Relationships
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'coupon_id');
    }

    public function context(): MorphTo
    {
        return $this->morphTo();
    }
}