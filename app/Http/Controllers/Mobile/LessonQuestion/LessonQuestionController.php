<?php

namespace App\Http\Controllers\Mobile\LessonQuestion;

use App\Constants\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Text\TextRequest;
use App\Http\Resources\LessonQuestion\LessonQuestionResource;
use App\Services\Lesson\LessonQuestionService;
use Illuminate\Http\Request;

class LessonQuestionController extends Controller
{
    public function __construct(
        protected LessonQuestionService $lessonQuestionService,
    ) {}

    public function index(Request $request, $lesson_id)
    {
        return success(
            $this->lessonQuestionService->get($request->all(), $lesson_id),
            ApiMessages::MSG_SUCCESS,
            LessonQuestionResource::class,
            $request->has('per_page')
        );
    }

    public function getForStudent(Request $request, $lesson_id)
    {
        return success(
            $this->lessonQuestionService->getForStudent($request->all(), $lesson_id, auth()->id()),
            ApiMessages::MSG_SUCCESS,
            LessonQuestionResource::class,
            $request->has('per_page')
        );
    }

    public function ask(TextRequest $request, $lesson_id)
    {
        return success(
            $this->lessonQuestionService->ask($request->all(), $lesson_id),
            ApiMessages::MSG_SUCCESS,
        );
    }

    public function answer(TextRequest $request, $question_id)
    {
        return success(
            $this->lessonQuestionService->answer($request->all(), $question_id),
            ApiMessages::MSG_SUCCESS,
        );
    }
}
