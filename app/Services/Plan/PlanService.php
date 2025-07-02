<?php

namespace App\Services\Plan;

use App\Services\Transaction\TransactionService;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Doctor;
use App\Models\Transaction;
use App\Models\Subscription;
use App\Constants\ExceptionMessages;
use App\Services\Base\ContextService;

/**
 * Class PlanService.
 */
class PlanService
{

    protected $contextService;
    protected $transactionService;
    public function __construct(ContextService $contextService , TransactionService $transactionService)
    {
        $this->contextService = $contextService;
        $this->transactionService = $transactionService;
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
        return Plan::findByIdOrFail($id  , ['subscripedDoctors']);
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
        $last_date = Subscription::latest('id')->first()->end_at;
        // dd($last_date);
        return $last_date;
    }

    public function subscripedBefore($doctor_id)
    {
        return Subscription::where('doctor_id' , $doctor_id)->exists();
    }

    public function getDateToStartNewSubscription()
    {
        $last_subscription_date = $this->getLatestSubscripedPlanDate();

        return Carbon::parse($last_subscription_date)->addDay()->format('Y-m-d');
    }

    public function hasActivePlan($doctor_id)
    {
        return Subscription::where('doctor_id' , $doctor_id)->active()->exists();
    }

    public function getDoctorActiveSubscription($doctor_id)
    {
        return Subscription::where('doctor_id' , $doctor_id)->active()->first();
    }

    public function getLatestSubscripedPlanId($doctor_id)
    {
        return Subscription::where('doctor_id' , $doctor_id)->latest('id')->first()->id;
    }

    public function subscripe($plan_id , $doctor_id)
    {
        $plan = Plan::findByIdOrFail($plan_id);

        $this->contextService->checkIfPlanIsPublished($plan);

        $dateToBegin = now()->format('Y-m-d');
        $dateToEnd = Carbon::parse($dateToBegin)->addDays($plan->number_of_days);
        
        if($this->subscripedBefore($doctor_id))
        {
            $dateToBegin = $this->getDateToStartNewSubscription();
            $dateToEnd = Carbon::parse($dateToBegin)->addDays($plan->number_of_days);
        }

        $doctor = Doctor::find($doctor_id);


        $price_after_discount = ($plan->discount_end_at > now()) 
                                        ? $plan->price - ($plan->price * ($plan->discount_percentage / 100)) 
                                        : $plan->price;

        $doctor->plans()->attach(
                $plan->id,
                [
                    'original_price' => $plan->price,
                    'price_after_discount' => $price_after_discount,
                    'discount_percentage' => $plan->discount_percentage,
                    'start_at' => $dateToBegin,
                    'end_at' => $dateToEnd,
                    'number_of_days' => $plan->number_of_days,
                    'is_active' => $this->hasActivePlan($doctor_id) ? 0 : 1,
                ]
            );

        $subscription_id = $this->getLatestSubscripedPlanId($doctor_id);

        $this->transactionService->generateTransaction(
              $doctor_id,
              $subscription_id,
              $price_after_discount  
            );
    }

    public function filterSubscriptions($data)
    {
        return getOrPaginate(
            Subscription::doctorId()->filter($data),
            $data
        );
    }

    public function getPublishedPlans($data)
    {
        return getOrPaginate(
            Plan::published(),
            $data
        );
    }

    public function processSubscriptionsData()
    {
        $current_plan = $this->getDoctorActiveSubscription(doctor_id());

        if($current_plan->end_at < now())
        {
            $current_plan->is_active = 0;
            $current_plan->save();

            $possible_next_plan = Subscription::where('doctor_id' , doctor_id())
                ->whereDate('start_at' , '<=' , now())
                ->whereDate('end_at' , '>=' , now())
                ->first();
            
            if($possible_next_plan)
            {
                $possible_next_plan->is_active = 1;
                $possible_next_plan->save();
            }
        }
    }
}
