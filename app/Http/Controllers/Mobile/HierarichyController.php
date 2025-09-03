<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\Unit\UnitResource;
use App\Services\Hierarichy\HierarichyService;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\SubUnit\SubUnitResource;
use App\Services\Administration\ResponsibilityService;
use App\Http\Resources\Responsibility\ResponsibilityResource;

class HierarichyController extends Controller
{
    public function __construct(
        protected HierarichyService $hierarichyService,
        protected ResponsibilityService $responsibilityService
    ) {}

    public function getSubject($subject_id)
    {
        return success(
            $this->hierarichyService->getSubject($subject_id),
            ApiMessages::MSG_SUCCESS,
            SubjectResource::class,
        );
    }

    public function getUnit($unit_id)
    {
        return success(
            $this->hierarichyService->getUnit($unit_id),
            ApiMessages::MSG_SUCCESS,
            UnitResource::class,
        );
    }

    public function getSubUnit($sub_unit_id)
    {
        return success(
            $this->hierarichyService->getSubUnit($sub_unit_id),
            ApiMessages::MSG_SUCCESS,
            SubUnitResource::class,
        );
    }

    public function getUnitDetails($unit_id)
    {
        return success(
            $this->hierarichyService->getUnitDetails($unit_id),
            ApiMessages::MSG_SUCCESS,
            UnitResource::class,
        );
    }

    public function getResponsibilitiesByTeacherId(Request $request , $teacher_id)
    {
        return success(
            $this->responsibilityService->getResponsibilitiesByTeacherId($request->all() , $teacher_id , true),
            ApiMessages::MSG_SUCCESS,
            ResponsibilityResource::class,
            $request->has('per_page')
        );
    }
}
