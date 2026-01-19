<?php

namespace App\Http\Controllers\Mobile\ScreenShot;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\ScreenShot\ScreenShotService;

class ScreenShotController extends Controller
{
    public function __construct(
        protected ScreenShotService $screenShotService,
    ) {}

    public function takeShot(Request $request)
    {
        return success(
            $this->screenShotService->takeShot($request->total_number_taken),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function getAvailableNumberOfShots()
    {
        return success(
            $this->screenShotService->getAvailableNumberOfShots(auth()->id()),
            ApiMessages::MSG_SUCCESS,
        );
    }
}
