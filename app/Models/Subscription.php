<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Subscription extends Pivot
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'subscriptions';
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeDoctorId($query)
    {
        return $query->where('doctor_id' , doctor_id());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active' , 1);
    }

    public function scopeFilter($query , $data)
    {
        return 
        $query
        
        ->when(isset($data['is_active']) , function($query) use ($data) {
            $query->Where('is_active' , $data['is_active']);
        })

        ->when(isset($data['expired_subscriptions']) && $data['expired_subscriptions'] , function($query) {
            $query->Where('end_at' ,'<', now());
        })

        ->when(isset($data['coming_subscriptions']) && $data['coming_subscriptions'] , function($query) {
            $query->Where('start_at' ,'>', now());
        });
    }
}
