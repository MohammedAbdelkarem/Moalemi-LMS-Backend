<?php

namespace App\Services\Transaction;

use App\Models\Coupon;
use App\Models\Transaction;
use App\Enums\AccessTypeEnum;
use App\Enums\CouponTypeEnum;
use App\Models\UnlockedContext;
use App\Enums\TransactionTypeEnum;
use App\Constants\ExceptionMessages;

/**
 * Class TransactionService.
 */
class TransactionService
{
    public function createStudentCupon($data)
    {
        $cupon = Coupon::create([
            'coupon' => generateUniqueCoupon(),
            'amount' => $data['amount'],
            'type' => CouponTypeEnum::STUDENT_ONE_TIME,
        ]);

        return $cupon;
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

    public function useStudentCupon($cupon)
    {
        $cupon = Coupon::where('coupon', $cupon)
            ->where('is_expired', 0)
            ->where('type', CouponTypeEnum::STUDENT_ONE_TIME->value)
            ->first();

        if(!$cupon)
            return notFoundFailure([] , ExceptionMessages::MSG_CUPON_NOT_FOUND);
        

        $cupon->update([
            'number_of_uses' => $cupon->number_of_uses + 1,
            'is_expired' => 1,
        ]);

        auth()->user()->balance += $cupon->amount;
        auth()->user()->save();

        $transaction = Transaction::create([
            'amount' => $cupon->amount,
            'user_id' => auth()->id(),
            'coupon_id' => $cupon->id,
            'transaction_type' => TransactionTypeEnum::COUPON_CHARGE->value,
        ]);

        return $transaction;
    }

    public function useContextCupon($cupon)
    {
        $cupon = Coupon::where('coupon', $cupon)
            ->where('is_expired', 0)
            ->where('type', CouponTypeEnum::CONTEXT_MANY_TIMES->value)
            ->first();

        if(!$cupon)
            return notFoundFailure([] , ExceptionMessages::MSG_CUPON_NOT_FOUND);

        $this->checkIflreadyUnlockedForCupon($cupon);

        $unlockedContext = UnlockedContext::create([
            'user_id' => auth()->id(),
            'context_id' => $cupon->context_id,
            'context_type' => $cupon->context_type,
        ]);
        
        $cupon->update([
            'number_of_uses' => $cupon->number_of_uses + 1,
        ]);
            
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'coupon_id' => $cupon->id,
            'transaction_type' => TransactionTypeEnum::COUPON_PURCHASE->value,
            'unlocked_context_id' => $unlockedContext->id,
        ]);

        return $transaction;
    }

    public function directPurchase($data)
    {
        $context = getModel($data['context_type'])::findByIdOrFail($data['context_id']);
        $balance = auth()->user()->balance;

        $this->checkIflreadyUnlockedForDirectPurchase($context->id , getModel($data['context_type']));

        if($context->access_type == AccessTypeEnum::FREE->value)
        {
            $unlockedContext = UnlockedContext::create([
                'user_id' => auth()->id(),
                'context_id' => $context->id,
                'context_type' => getModel($data['context_type']),
            ]);
        }
        else
        {
            if($balance < $context->price)
                return forbiddenFailure([] , ExceptionMessages::MSG_INSUFFICIENT_BALANCE);
            
            $unlockedContext = UnlockedContext::create([
                'user_id' => auth()->id(),
                'context_id' => $context->id,
                'context_type' => getModel($data['context_type']),
            ]);

            auth()->user()->balance -= $context->price;
            auth()->user()->save();

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'amount' => $context->price,
                'transaction_type' => TransactionTypeEnum::DIRECT_PURCHASE->value,
                'unlocked_context_id' => $unlockedContext->id,
            ]);
        }
    }

    private function checkIflreadyUnlockedForCupon($cupon)
    {
        $alreadyUnlocked = UnlockedContext::where('user_id', auth()->id())
            ->where('context_id', $cupon->context_id)
            ->where('context_type', $cupon->context_type)
            ->first();

        if($alreadyUnlocked)
            return forbiddenFailure([] , ExceptionMessages::MSG_CONTEXT_ALREADY_UNLOCKED);
    }

    private function checkIflreadyUnlockedForDirectPurchase($context_id , $context_type)
    {
        $alreadyUnlocked = UnlockedContext::where('user_id', auth()->id())
            ->where('context_id', $context_id)
            ->where('context_type', $context_type)
            ->first();

        if($alreadyUnlocked)
            return forbiddenFailure([] , ExceptionMessages::MSG_CONTEXT_ALREADY_UNLOCKED);
    }
}
