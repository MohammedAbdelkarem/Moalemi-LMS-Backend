<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'subscriptions')
                    ->using(Subscription::class)
                    ->withPivot(
                        'price',
                         'discount_percentage',
                         'start_at' ,
                         'end_at',
                         'number_of_days',
                         'number_of_remaining_days',
                         'is_active'
                    )
                    ->withTimestamps();
    }

    public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    {
        return findByIdOrFail(
            self::class,
            $id,
            GenderEnum::FEMALE,
            Resources::RES_PLAN,
            $with,
            $withTrashed,
            $selectedColumns
        );
    }

}
