<?php

namespace App\Services\Quiz;

use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\StudentAnswer;
use App\Models\Answer;
use App\Enums\QuizResultEnum;
use App\Services\MainService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizSolvingService extends MainService
{
    public function startQuiz($validatedData)
    {
        $quiz = Quiz::findByIdOrFail($validatedData['quiz_id'], ['questions.answers']);
        
        // Check if student already has an in-progress quiz result
        $existingResult = QuizResult::where('quiz_id', $validatedData['quiz_id'])
            ->where('student_id', $validatedData['student_id'])
            ->where('result', QuizResultEnum::IN_PROGRESS->value)
            ->first();

        if ($existingResult) {
            return $existingResult->load(['quiz.questions.answers']);
        }

        // Create new quiz result
        $quizResult = QuizResult::create([
            'quiz_id' => $validatedData['quiz_id'],
            'student_id' => $validatedData['student_id'],
            'result' => QuizResultEnum::IN_PROGRESS->value,
            'degree' => 0,
            'number_of_correct_answers' => 0,
            'number_of_wrong_answers' => 0,
            'number_of_answered_questions' => 0,
            'taken_period' => 0,
        ]);

        return $quizResult->load(['quiz.questions.answers']);
    }

    public function submitAnswer($validatedData)
    {
        return DB::transaction(function () use ($validatedData) {
            $quizResult = QuizResult::findByIdOrFail($validatedData['quiz_result_id']);
            
            // Check if quiz is still in progress
            if ($quizResult->result !== QuizResultEnum::IN_PROGRESS->value) {
                throw new \Exception('Quiz is no longer in progress');
            }

            $question = $quizResult->quiz->questions()->findOrFail($validatedData['question_id']);
            
            // Handle different question types
            if ($question->type === 'one_select') {
                return $this->handleOneSelectAnswer($quizResult, $question, $validatedData['answer_id']);
            } elseif ($question->type === 'multiple_select') {
                return $this->handleMultipleSelectAnswer($quizResult, $question, $validatedData['answer_ids']);
            }

            throw new \Exception('Invalid question type');
        });
    }

    private function handleOneSelectAnswer($quizResult, $question, $answerId)
    {
        // Check if answer already exists for this question
        $existingAnswer = StudentAnswer::where('quiz_result_id', $quizResult->id)
            ->where('question_id', $question->id)
            ->first();

        if ($existingAnswer) {
            // Update existing answer
            $answer = Answer::findOrFail($answerId);
            $existingAnswer->update([
                'answer_id' => $answerId,
                'is_correct' => $answer->is_correct,
            ]);
        } else {
            // Create new answer
            $answer = Answer::findOrFail($answerId);
            StudentAnswer::create([
                'quiz_result_id' => $quizResult->id,
                'question_id' => $question->id,
                'answer_id' => $answerId,
                'is_correct' => $answer->is_correct,
            ]);
        }

        return $this->updateQuizResultStats($quizResult);
    }

    private function handleMultipleSelectAnswer($quizResult, $question, $answerIds)
    {
        // Delete existing answers for this question
        StudentAnswer::where('quiz_result_id', $quizResult->id)
            ->where('question_id', $question->id)
            ->delete();

        // Create new answers
        foreach ($answerIds as $answerId) {
            $answer = Answer::findOrFail($answerId);
            StudentAnswer::create([
                'quiz_result_id' => $quizResult->id,
                'question_id' => $question->id,
                'answer_id' => $answerId,
                'is_correct' => $answer->is_correct,
            ]);
        }

        return $this->updateQuizResultStats($quizResult);
    }

    private function updateQuizResultStats($quizResult)
    {
        $studentAnswers = StudentAnswer::where('quiz_result_id', $quizResult->id)->get();
        
        $correctAnswers = $studentAnswers->where('is_correct', true)->count();
        $wrongAnswers = $studentAnswers->where('is_correct', false)->count();
        $answeredQuestions = $studentAnswers->pluck('question_id')->unique()->count();
        
        // Calculate degree (percentage)
        $totalQuestions = $quizResult->quiz->questions()->count();
        $degree = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        $quizResult->update([
            'degree' => $degree,
            'number_of_correct_answers' => $correctAnswers,
            'number_of_wrong_answers' => $wrongAnswers,
            'number_of_answered_questions' => $answeredQuestions,
        ]);

        return $quizResult->load(['quiz.questions.answers', 'studentAnswers.answer']);
    }

    public function completeQuiz($quizResultId)
    {
        $quizResult = QuizResult::findByIdOrFail($quizResultId);
        
        if ($quizResult->result !== QuizResultEnum::IN_PROGRESS->value) {
            throw new \Exception('Quiz is not in progress');
        }

        // Calculate final result based on degree
        $passingScore = 60; // You can make this configurable
        $finalResult = $quizResult->degree >= $passingScore 
            ? QuizResultEnum::SUCCESS->value 
            : QuizResultEnum::FAIL->value;

        $quizResult->update([
            'result' => $finalResult,
            'taken_period' => $this->calculateTakenPeriod($quizResult),
        ]);

        return $quizResult->load(['quiz.questions.answers', 'studentAnswers.answer']);
    }

    private function calculateTakenPeriod($quizResult)
    {
        // Calculate time difference in minutes
        $startTime = $quizResult->created_at;
        $endTime = now();
        
        return $startTime->diffInMinutes($endTime);
    }
}
