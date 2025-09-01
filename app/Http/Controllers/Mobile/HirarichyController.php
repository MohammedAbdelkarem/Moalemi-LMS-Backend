<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\CLevel\CLevelService;
use App\Services\ELevel\ELevelService;

class HirarichyController extends Controller
{
    public function __construct(
        protected ELevelService $eLevelService,
        protected CLevelService $cLevelService,
    ) {}

    public function e_levels()
    {
        return success($this->eLevelService->getList(), ApiMessages::MSG_SUCCESS);
    }

    public function c_levels($e_level_id)
    {
        return success($this->cLevelService->getList($e_level_id), ApiMessages::MSG_SUCCESS);
    }
}
