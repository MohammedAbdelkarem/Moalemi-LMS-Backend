<?php

namespace App\Http\Controllers\System\Info;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\Info\AboutUsRequest;
use App\Http\Resources\System\Info\AboutUsResource;
use App\Models\System\Info\AboutUs;
use App\Services\System\Info\AboutUsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function __construct(
        protected AboutUsService $aboutUsService
    ) {}

    public function index(): JsonResponse
    {
        return success(
            $this->aboutUsService->index(),
            ApiMessages::MSG_SUCCESS,
            AboutUsResource::class,
        );
    }

    public function store(AboutUsRequest $request): JsonResponse
    {
        return createdSuccess(
            $this->aboutUsService->store($request->validated()),
            ApiMessages::MSG_SUCCESS
        );
    }

    public function show(): JsonResponse
    {
        return success(
            $this->aboutUsService->show(request()->lang),
            ApiMessages::MSG_SUCCESS,
            AboutUsResource::class,
        );
    }
}
