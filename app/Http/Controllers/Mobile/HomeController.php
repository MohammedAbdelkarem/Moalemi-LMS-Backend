<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\StudentHomeService;

class HomeController extends Controller
{
    public function __construct(
        protected StudentHomeService $studentHomeService,
    ) {}

    public function home()
    {
        return success(
            $this->studentHomeService->get(),
            ApiMessages::MSG_SUCCESS
        );
    }
}
