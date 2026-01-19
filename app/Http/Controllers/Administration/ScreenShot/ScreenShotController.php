<?php

namespace App\Http\Controllers\Administration\ScreenShot;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\ScreenShot\ScreenShotService;
use App\Http\Requests\ScreenShot\ScreenShotRequest;
use App\Http\Resources\ScreenShot\ScreenShotResource;

class ScreenShotController extends Controller
{
    public function __construct(
        protected ScreenShotService $screenShotService,
    ) {}

    public function index(Request $request, $user_id)
    {
        return success(
            $this->screenShotService->getByUserId($user_id, $request->all()),
            ApiMessages::MSG_SUCCESS,
            ScreenShotResource::class,
            $request->has('per_page')
        );
    }

    public function store(ScreenShotRequest $request)
    {
        return createdSuccess(
            $this->screenShotService->create($request->validated()),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function disable($id)
    {
        return success(
            $this->screenShotService->disable($id),
            ApiMessages::MSG_SUCCESS,
        );
    }


}
