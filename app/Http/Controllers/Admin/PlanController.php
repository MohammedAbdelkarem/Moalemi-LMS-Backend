<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Services\Plan\PlanService;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetItemsRequest;
use App\Http\Requests\Plan\CreatePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;
use App\Http\Resources\PlanResource;

class PlanController extends Controller
{
    public function __construct(
        protected PlanService $planService,
    ) {}

    public function index(GetItemsRequest $request)
    {
        return success(
            $this->planService->getAll( $request->validated()),
            ApiMessages::MSG_SUCCESS,
            PlanResource::class,
            $request->has('per_page')
        );
    }

    public function show($id)
    {
        return success(
            $this->planService->show($id),
            ApiMessages::MSG_SUCCESS,
            PlanResource::class
        );
    }

    public function store(CreatePlanRequest $request)
    {
        return createdSuccess(
            $this->planService->store($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function update(UpdatePlanRequest $request , $id)
    {
        return success(
            $this->planService->update($request->validated() , $id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function destroy($id)
    {
        return success(
            $this->planService->destroy($id),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function changePublishStatus($id)
    {
        return success(
            $this->planService->changePublishStatus($id),
            ApiMessages::MSG_UPDATED
        );
    }
}
