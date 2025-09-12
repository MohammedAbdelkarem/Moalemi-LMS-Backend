<?php

namespace App\Services\Quiz;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\QuizResult;
use App\Enums\QuizResultEnum;
use App\Models\StudentAnswer;
use App\Services\MainService;

class SolvingService extends MainService
{
    public function startQuiz($id)
    {
        $existSolution = is_solved($id, auth()->id(), true);

        if($existSolution)
            $existSolution->delete();

        $quiz = Quiz::findByIdOrFail($id);

        
        $quizResult = QuizResult::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'taken_period' => 0,
            'number_of_correct_answers' => 0,
            'number_of_wrong_answers' => 0,
            'number_of_answered_questions' => 0,
            'result' => QuizResultEnum::IN_PROGRESS->value,
            'degree' => 0,
        ]);

        return $quizResult;
    }

    public function solveQuiz($data)
    {
        $quizResult = QuizResult::findByIdOrFail($data['quiz_result_id']);

        $quiz = Quiz::findByIdOrFail($quizResult->quiz_id);
        $quizResult->number_of_answered_questions = count($data['answers']);
        $quizResult->taken_period = $data['taken_period'];

        foreach($data['answers'] as $answer_id)
        {
            $answer = Answer::find($answer_id);

            $studentAnswer = StudentAnswer::create([
                'quiz_result_id' => $quizResult->id,
                'question_id' => $answer->question_id,
                'answer_id' => $answer->id,
                'is_correct' => $answer->is_correct,
            ]);

            if($answer->is_correct)
                $quizResult->number_of_correct_answers++;
            else
                $quizResult->number_of_wrong_answers++;
        }

        $result = $quiz->one_question_degree * $quizResult->number_of_correct_answers;

        $quizResult->result = $result >= $quiz->pass_degree 
            ? QuizResultEnum::SUCCESS->value 
            : QuizResultEnum::FAIL->value;
        
        $quizResult->degree = $result;
        
        $quizResult->save();

        return $quizResult;
    }

    public function showPrevSolution($quiz_id)
    {
        $quizResult = QuizResult::where('quiz_id', $quiz_id)
            ->where('student_id', auth()->id())
            ->with('quiz.questions.answers.studentAnswers')
            ->first();

        return $quizResult;
    }
}
 