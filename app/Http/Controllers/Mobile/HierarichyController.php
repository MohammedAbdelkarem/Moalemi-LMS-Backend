<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Services\Lesson\LessonService;
use App\Http\Resources\Unit\UnitResource;
use App\Services\Hierarichy\HierarichyService;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\SubUnit\SubUnitResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Services\Administration\ResponsibilityService;
use App\Http\Resources\Responsibility\ResponsibilityResource;
use App\Services\Administration\Teacher\TeacherService;

class HierarichyController extends Controller
{
    public function __construct(
        protected HierarichyService $hierarichyService,
        protected ResponsibilityService $responsibilityService,
        protected LessonService $lessonService,
        protected TeacherService $teacherService,
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

    public function getResponsibilitiesByTeacherId(Request $request , $teacher_id , $c_level_id)
    {
        return success(
            $this->responsibilityService->getResponsibilitiesByTeacherId($request->all() , $teacher_id , true , $c_level_id),
            ApiMessages::MSG_SUCCESS,
            ResponsibilityResource::class,
            $request->has('per_page')
        );
    }

    public function recordLessonView($lesson_id)
    {
        $student_id = auth()->id();
        
        $this->lessonService->recordLessonView($lesson_id, $student_id);
        
        return success(
            [],
            ApiMessages::MSG_SUCCESS
        );
    }

    public function getTeacherDetails($teacher_id)
    {
        return success(
            $this->teacherService->getTeacherDetails($teacher_id),
            ApiMessages::MSG_SUCCESS,
            TeacherResource::class,
        );
    }
}
