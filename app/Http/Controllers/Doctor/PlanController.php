<?php

namespace App\Http\Controllers\Doctor;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
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
            $this->planService->subscripe($id),
            ApiMessages::MSG_SUCCESS
        );
    }
}
