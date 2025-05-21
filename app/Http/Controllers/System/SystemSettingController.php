<?php

namespace App\Http\Controllers\System;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\SystemSettingRequest;
use App\Http\Resources\System\SystemSettingResource;
use App\Models\System\SystemSetting;
use App\Services\System\SystemSettingService;
use Illuminate\Http\JsonResponse;

class SystemSettingController extends Controller
{
    public function __construct(
        protected SystemSettingService $systemSettingService
    ) {}

    public function index(): JsonResponse
    {
        return success(
            $this->systemSettingService->index(),
            ApiMessages::MSG_SUCCESS,
            SystemSettingResource::class,
        );
    }

    public function update(SystemSettingRequest $request, string $id): JsonResponse
    {
        return success(
            $this->systemSettingService->update($id, $request->validated()),
            ApiMessages::MSG_UPDATED
        );
    }
}
