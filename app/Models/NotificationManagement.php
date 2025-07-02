<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationManagement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'steps_notification',
        'water_notification',
        'sleep_notification',
        'weight_notification',
        'general_notification',
        'articles_notification',
        'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // /**
    //  * @return \App\Models\NotificationManagement
    //  */
    // public static function findByIdOrFail($id, $with = [], $withTrashed = false, $selectedColumns = null)
    // {
    //     return findByIdOrFail(
    //         self::class,
    //         $id,
    //         GenderEnum::MALE,
    //         Resources::RES_MODEL,
    //         $with,
    //         $withTrashed,
    //         $selectedColumns
    //     );
    // }
}
