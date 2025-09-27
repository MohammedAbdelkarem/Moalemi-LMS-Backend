<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Lesson\LessonService;
use App\Constants\ApiMessages;
use App\Http\Resources\Lesson\LessonResource;

class TeacherController extends Controller
{
    public function __construct(
        protected LessonService $lessonService
    ) {}

    public function getTeacherLessons(Request $request)
    {
        return success(
            $this->lessonService->getTeacherLessons(auth()->id(), $request->all()),
            ApiMessages::MSG_SUCCESS,
            LessonResource::class,
            $request->has('per_page')
        );
    }
}
