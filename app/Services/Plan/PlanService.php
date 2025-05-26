<?php

namespace App\Services\Plan;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Doctor;
use App\Constants\ExceptionMessages;
use App\Services\Base\ContextService;

/**
 * Class PlanService.
 */
class PlanService
{

    protected $contextService;
    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }
    public function getAll($data)
    {
        return getOrPaginate(
            Plan::with('doctors'),
            $data
        );
    }

    public function show($id)
    {
        return plan::findByIdOrFail($id  , ['doctors']);
    }

    public function store($data)
    {
        $item = Plan::create($data);

        $item->save();
    }

    public function update($data , $id)
    {
        $item =  Plan::findByIdOrFail($id);

        $item->update($data);

        $item->save();
    }

    public function destroy($id)
    {
        $item = Plan::findByIdOrFail($id);

        if($item->doctors()->count() > 0)
            return forbiddenFailure(null , ExceptionMessages::MSG_CAN_NOT_DELETE_PLAN_CUZ_HAS_SUBSCRIPED_DOCTORS);

        $item->delete();
    }

    public function changePublishStatus($id)
    {
        $item = Plan::findByIdOrFail($id);

        $this->contextService->changePublishStatus($item);
    }

    public function getLatestSubscripedPlanDate()
    {
        
        return Doctor::find(doctor_id())->plans()->orderBy('id', 'desc')->latest()->pivot->end_at;
    }

    public function getDateToStartNewSubscription()
    {
        $last_subscription_date = $this->getLatestSubscripedPlanDate();

        return Carbon::parse($last_subscription_date)->addDay()->format('Y-m-d');
    }

    public function subscripe($plan_id)
    {
        $plan = Plan::findByIdOrFail($plan_id);

        $this->contextService->checkIfPlanIsPublished($plan);

        $doctor = Doctor::find(doctor_id());

        $dateToBegin = $this->getDateToStartNewSubscription();
        $dateToEnd = Carbon::parse($dateToBegin)->addDays($plan->number_of_days);

        $doctor->plans()->attach(
                $plan->id,
                [
                    'original_price' => $plan->price,
                    'price_after_discount' => ($plan->discount_end_at > now()) 
                                        ? $plan->price - ($plan->price * ($plan->discount_percentage / 100)) 
                                        : $plan->price,
                    'discount_percentage' => $plan->discount_percentage,
                    'start_at' => $dateToBegin,
                    'end_at' => $dateToEnd,
                    'number_of_days' => $plan->number_of_days,
                    'is_active' => 1,
                ]
            );
    }
}
