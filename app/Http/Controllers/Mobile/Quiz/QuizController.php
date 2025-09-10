<?php

namespace App\Http\Controllers\Mobile\Quiz;

use Illuminate\Http\Request;
use App\Services\Quiz\QuizService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Quiz\QuizResource;
use App\Constants\ApiMessages;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService,
    ) {}

    public function search(Request $request)
    {
        return success(
            $this->quizService->search($request->all(), $request->student_id ?? auth()->id()),
            ApiMessages::MSG_SUCCESS,
            QuizResource::class,
            $request->has('per_page')
        );
    }

    public function filter(Request $request)
    {
        return success(
            $this->quizService->filter($request->all(), $request->student_id ?? auth()->id()),
            ApiMessages::MSG_SUCCESS,
            QuizResource::class,
            $request->has('per_page')
        );
    }

    public function getPurchasedQuizzes(Request $request)
    {
        return success(
            $this->quizService->getPurchasedQuizzes($request->student_id ?? auth()->id(), $request->context_type, $request->all()),
            ApiMessages::MSG_SUCCESS,
            QuizResource::class,
            $request->has('per_page')
        );
    }
    
}
