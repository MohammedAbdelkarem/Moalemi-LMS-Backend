<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Constants\Resources;
use App\Enums\PublishStatusEnum;
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
                        'original_price',
                        'price_after_discount',
                         'discount_percentage',
                         'start_at' ,
                         'end_at',
                         'number_of_days',
                         'is_active'
                    )
                    ->withTimestamps();
    }

    
    public function subscripedDoctors()
    {
        return $this->belongsToMany(Doctor::class, 'subscriptions')
                    ->using(Subscription::class)
                    ->withPivot(
                        'original_price',
                        'price_after_discount',
                         'discount_percentage',
                         'start_at' ,
                         'end_at',
                         'number_of_days',
                         'is_active'
                    )
                    ->withTimestamps()
                    ->wherePivot('is_active' , 1);
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

    
    public function scopePublished($query)
    {
        return $query->where('publish_status' , PublishStatusEnum::PUBLISHED);
    }

    public function scopeFilter($query , $data)
    {
        return $query

        // ->when(isset($data['title']) , function($query) use ($data) {
        //     $query->where('title' , 'like' , $data['title']);
        // })

        ->when(isset($data['start_price']) , function($query) use ($data) {
            $query->where('price' , '>=' , $data['start_price']);
        })

        ->when(isset($data['end_price']) , function($query) use ($data) {
            $query->where('price' , '<=' , $data['end_price']);
        })

        ->when(isset($data['has_discount']) && $data['has_discount'] , function($query) use ($data) {
            $query->where('discount_percentage' , '!=' , 0)
                    ->where('discount_end_at' , '>' , now());
        });


    }

}
