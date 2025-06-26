<?php

namespace App\Http\Controllers\Patient;

use App\Http\Requests\Health\TimeRequest;
use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Services\HealthService;
use App\Http\Controllers\Controller;

class HealthController extends Controller
{
    public function __construct(
        protected HealthService $healthService
    ){}

    public function getWeightHistory(Request $request)
    {
        return success(
            $this->healthService->getWeightHistory($request->all()),
            ApiMessages::MSG_SUCCESS,
            null,
            $request->has('per_page')
        );
    }

    public function updateWeight($current_weight)
    {
        return success(
            $this->healthService->updateWeight($current_weight),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getBMI()
    {
        return success(
            $this->healthService->BMI(),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getWaterGoal()
    {
        return success(
            $this->healthService->getWaterGoal(),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getWaterHistory(Request $request)
    {
        return success(
            $this->healthService->getWaterHistory($request->all()),
            ApiMessages::MSG_SUCCESS,
            null,
            $request->has('per_page')
        );
    }

    public function storeWater(TimeRequest $request)
    {
        return success(
            $this->healthService->storeWater($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getSleepHistory(Request $request)
    {
        return success(
            $this->healthService->getSleepHistory($request->all()),
            ApiMessages::MSG_SUCCESS,
            null,
            $request->has('per_page')
        );
    }

    public function storeSleep($amount)
    {
        return success(
            $this->healthService->storeSleep($amount),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getStepHistory(Request $request)
    {
        return success(
            $this->healthService->getStepsHistory($request->all()),
            ApiMessages::MSG_SUCCESS,
            null,
            $request->has('per_page')
        );
    }

    public function storeStep(TimeRequest $request)
    {
        return success(
            $this->healthService->storeSleep($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function setStepCalcAsActive($loginHistoryId)
    {
        return success(
            $this->healthService->setStepCalcAsActive($loginHistoryId),
            ApiMessages::MSG_SUCCESS,
        );
    }
}
