<?php

namespace App\Http\Controllers\Mobile\LessonQuestion;

use App\Http\Controllers\Controller;
use App\Services\Lesson\LessonQuestionService;
use Illuminate\Http\Request;

class LessonQuestionController extends Controller
{
    public function __construct(
        protected LessonQuestionService $lessonQuestionService,
    ) {}

    public function index(Request $request, $lesson_id)
    {
        return success($this->lessonQuestionService->get($request->all(), $lesson_id));
    }

    public function getForStudent(Request $request, $lesson_id)
    {
        return success($this->lessonQuestionService->getForStudent($request->all(), $lesson_id, auth()->id()));
    }

    public function ask(Request $request, $lesson_id)
    {
        return success($this->lessonQuestionService->ask($request->all(), $lesson_id));
    }

    public function answer(Request $request, $question_id)
    {
        return success($this->lessonQuestionService->answer($request->all(), $question_id));
    }
}
