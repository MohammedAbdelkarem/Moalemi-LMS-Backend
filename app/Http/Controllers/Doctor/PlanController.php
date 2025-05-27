<?php

namespace App\Http\Controllers\Doctor;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SubscriptionResouce;
use App\Services\Plan\PlanService;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct(
        protected PlanService $planService
    ) {}

    public function subscripe($id)
    {
        return success(
            $this->planService->subscripe($id , doctor_id()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getSubscriptions(Request $request)
    {
        return success(
            $this->planService->filterSubscriptions($request->all()),
            ApiMessages::MSG_SUCCESS,
            SubscriptionResouce::class,
            $request->has('per_page')
        );
    }

    public function getPlans(Request $request)
    {
        return success(
            $this->planService->getPublishedPlans($request->all()),
            ApiMessages::MSG_SUCCESS,
            PlanResource::class,
            $request->has('per_page')
        );
    }
}
