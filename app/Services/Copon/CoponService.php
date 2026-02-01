<?php

namespace App\Services\Copon;

use App\Models\Unit;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\SubUnit;
use App\Models\Transaction;
use App\Enums\AccessTypeEnum;
use App\Enums\CouponTypeEnum;
use App\Models\UnlockedContext;
use App\Constants\ExceptionMessages;
use App\Services\Transaction\TransactionService;

/**
 * Class CoponService.
 */
class CoponService
{

    public function __construct(
        protected TransactionService $transactionService
    ){}
    public function createOnePointsCupon($data)
    {
        $copon = Coupon::create([
            'coupon' => generateUniqueCoupon(),
            'type' => CouponTypeEnum::STUDENT_ONE_TIME,
            'amount' => $data['amount'],
            'user_id' => $data['user_id'],
        ]);

        if($data['direct_activate']){
            $transaction = $this->transactionService->useOnePointsCopon($copon->fresh()->coupon, $data['user_id']);

            return [
                'copon' => $copon->fresh(),
                'transaction' => $transaction,
            ];
        }

        return $copon->fresh();
    }
    public function createManyPointsCupon($data)
    {
        $copon = Coupon::create([
            'coupon' => generateUniqueCoupon(),
            'type' => CouponTypeEnum::STUDENT_ONE_TIME,
            'amount' => $data['amount'],
            'number_of_max_uses' => $data['number_of_max_uses'],
            'expired_at' => $data['expired_at'],
        ]);

        return $copon->fresh();
    }
    public function createOneContextCupon($data)
    {
        $context = getModel($data['context_type'])::find($data['context_id']);

        if($context->access_type == AccessTypeEnum::FREE->value)
            return forbiddenFailure([] , ExceptionMessages::MSG_CANNOT_CREATE_CUZ_CONTEXT_IS_FREE);

        $cupon = Coupon::create([
            'coupon' => generateUniqueCoupon(),
            'type' => CouponTypeEnum::CONTEXT_ONE_TIME,
            'user_id' => $data['user_id'],
            'context_id' => $data['context_id'],
            'context_type' => getModel($data['context_type']),
            'context_expired_at' => $data['context_expired_at'],
        ]);

        if($data['direct_activate']){
            $transaction = $this->transactionService->useContextCopon($cupon->fresh()->coupon, $data['user_id']);

            return [
                'copon' => $cupon->fresh(),
                'transaction' => $transaction,
            ];
        }

        return $cupon->fresh();
    }
    public function createManyContextCupon($data)
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
            'context_expired_at' => $data['context_expired_at'],
            'number_of_max_uses' => $data['number_of_max_uses'],
        ]);

        return $cupon->fresh();
    }
    public function get($data)
    {
        return getOrPaginate(
            Coupon::filter($data)->orderBy('created_at', 'desc')->with('context'),
            $data
        );
    }

    public function setAsExpired($data)
    {
        Coupon::whereIn('id', $data['ids'])
            ->update(['is_expired' => 1]);
    }

    public function delete($id)
    {
        $copon = Coupon::findByIdOrFail($id);

        if($copon->number_of_uses > 0)
            return forbiddenFailure([] , ExceptionMessages::MSG_CANNOT_DELETE_CUZ_HAS_USED);

        $copon->delete();
    }

    public function lockForStudentByCopon($copon_id , $userId)
    {
        $copon = Coupon::findByIdOrFail($copon_id);

        $transactions = Transaction::where('coupon_id', $copon->id)
            ->where('user_id', $userId)
            ->get();

        $unlockedContextIds = $transactions->pluck('unlocked_context_id')->toArray();

        foreach($unlockedContextIds as $unlockedContextId)
        {
            $unlockedContext = UnlockedContext::find($unlockedContextId);
            
            if($unlockedContext->context_type == Course::class)
            {
                $course = Course::find($unlockedContext->context_id);
                $subjectIds = Subject::where('course_id', $course->id)->pluck('id')->toArray();
                $unitIds = Unit::whereIn('subject_id', $subjectIds)->pluck('id')->toArray();
                $subUnitIds = SubUnit::whereIn('unit_id', $unitIds)->pluck('id')->toArray();
                $lessonIds = Lesson::whereIn('sub_unit_id', $subUnitIds)->pluck('id')->toArray();

                UnlockedContext::whereIn('context_id', $subjectIds)
                    ->where('context_type', Subject::class)
                    ->where('user_id', $userId)
                    ->delete();
                UnlockedContext::whereIn('context_id', $unitIds)
                    ->where('context_type', Unit::class)
                    ->where('user_id', $userId)
                    ->delete();
                UnlockedContext::whereIn('context_id', $subUnitIds)
                    ->where('context_type', SubUnit::class)
                    ->where('user_id', $userId)
                    ->delete();
                UnlockedContext::whereIn('context_id', $lessonIds)
                    ->where('context_type', Lesson::class)
                    ->where('user_id', $userId)
                    ->delete();
            }
            else if($unlockedContext->context_type == Subject::class)
            {
                $subject = Subject::find($unlockedContext->context_id);
                $unitIds = Unit::where('subject_id', $subject->id)->pluck('id')->toArray();
                $subUnitIds = SubUnit::whereIn('unit_id', $unitIds)->pluck('id')->toArray();
                $lessonIds = Lesson::whereIn('sub_unit_id', $subUnitIds)->pluck('id')->toArray();

                UnlockedContext::whereIn('context_id', $unitIds)
                    ->where('context_type', Unit::class)
                    ->where('user_id', $userId)
                    ->delete();
                UnlockedContext::whereIn('context_id', $subUnitIds)
                    ->where('context_type', SubUnit::class)
                    ->where('user_id', $userId)
                    ->delete();
                UnlockedContext::whereIn('context_id', $lessonIds)
                    ->where('context_type', Lesson::class)
                    ->where('user_id', $userId)
                    ->delete();
            }
            UnlockedContext::whereIn('id', $unlockedContextIds)
                ->delete();
        }
    }
}
