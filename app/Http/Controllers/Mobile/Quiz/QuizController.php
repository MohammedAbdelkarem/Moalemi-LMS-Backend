<?php

namespace App\Http\Controllers\Mobile\Quiz;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Quiz\QuizService;
use App\Services\Quiz\QuizSolvingService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Quiz\QuizResource;
use App\Http\Requests\Quiz\StartQuizRequest;
use App\Http\Requests\Quiz\SubmitAnswerRequest;
use App\Constants\ApiMessages;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService,
        protected QuizSolvingService $quizSolvingService,
    ) {}

    public function search(Request $request)
    {
        return success(
            $this->quizService->search($request->all(), $request->student_id ?? Auth::id()),
            ApiMessages::MSG_SUCCESS,
            QuizResource::class,
            $request->has('per_page')
        );
    }

    public function filter(Request $request)
    {
        return success(
            $this->quizService->filter($request->all(), $request->student_id ?? Auth::id()),
            ApiMessages::MSG_SUCCESS,
            QuizResource::class,
            $request->has('per_page')
        );
    }

    public function getPurchasedQuizzes(Request $request)
    {
        return success(
            $this->quizService->getPurchasedQuizzes($request->student_id ?? Auth::id(), $request->context_type, $request->all()),
            ApiMessages::MSG_SUCCESS,
            QuizResource::class,
            $request->has('per_page')
        );
    }

    public function startQuiz(StartQuizRequest $request)
    {
        $quizResult = $this->quizSolvingService->startQuiz($request->validated());
        
        return success(
            $quizResult,
            ApiMessages::MSG_SUCCESS
        );
    }

    public function submitAnswer(SubmitAnswerRequest $request)
    {
        $quizResult = $this->quizSolvingService->submitAnswer($request->validated());
        
        return success(
            $quizResult,
            ApiMessages::MSG_SUCCESS
        );
    }

    public function completeQuiz(Request $request)
    {
        $request->validate([
            'quiz_result_id' => ['required', 'integer', 'exists:quiz_results,id'],
        ]);

        $quizResult = $this->quizSolvingService->completeQuiz($request->quiz_result_id);
        
        return success(
            $quizResult,
            ApiMessages::MSG_SUCCESS
        );
    }
    
}
