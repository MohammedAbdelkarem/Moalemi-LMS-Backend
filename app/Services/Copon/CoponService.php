<?php

namespace App\Services\Copon;

use App\Enums\CouponTypeEnum;
use App\Enums\AccessTypeEnum;
use App\Constants\ExceptionMessages;
use App\Models\Coupon;

/**
 * Class CoponService.
 */
class CoponService
{
    public function createStudentCupon($data)
    {
        for($i = 0; $i < $data['number_of_copons']; $i++) 
        {
            $cupon = Coupon::create([
                'coupon' => generateUniqueCoupon(),
                'amount' => $data['amount'],
                'type' => CouponTypeEnum::STUDENT_ONE_TIME,
            ]);
        }
    }
    public function createContextCupon($data)
    {
        $context = getModel($data['context_type'])::find($data['context_id']);

        if($context->access_type == AccessTypeEnum::FREE->value)
            return forbiddenFailure([] , ExceptionMessages::MSG_CANNOT_CREATE_CUZ_CONTEXT_IS_FREE);


        $cupon = Coupon::create([
            'coupon' => generateUniqueCoupon(),
            'type' => CouponTypeEnum::CONTEXT_MANY_TIMES,
            'expired_at' => $data['expired_at'],
            'context_id' => $data['context_id'],
            'context_type' => getModel($data['context_type']),
        ]);

        return $cupon;
    }
    public function get($data)
    {
        return getOrPaginate(
            Coupon::filter($data)->with('context'),
            $data
        );
    }

    public function setAsExpired($data)
    {
        Coupon::whereIn('id', $data['ids'])
            ->update(['is_expired' => 1]);
    }
}
