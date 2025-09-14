<?php

namespace App\Services\Question;

use App\Models\Answer;
use App\Models\Question;
use App\Services\MainService;
use App\Enums\QuestionTypeEnum;
use App\Constants\MediaCollection;
use Illuminate\Support\Facades\DB;
use App\Constants\ExceptionMessages;
use App\Services\Base\ContextService;

class QuestionService extends MainService
{
    public function __construct(
        protected ContextService $contextService,
    ) {}

    public function getAll($data)
    {
        $query = Question::orderBy('created_at', 'desc')
                ->with(['unit', 'subUnit', 'answers']);

        if (isset($data['unit_id'])) {
            $query->where('unit_id', $data['unit_id']);
        }

        if (isset($data['sub_unit_id'])) {
            $query->where('sub_unit_id', $data['sub_unit_id']);
        }

        if (isset($data['type'])) {
            $query->where('type', $data['type']);
        }

        return getOrPaginate($query, $data);
    }

    public function show($id)
    {
        return Question::findByIdOrFail($id, ['unit', 'subUnit', 'answers']);
    }

    public function store($validatedData)
    {
        $this->chackeQuestionCorrectAnswersCount($validatedData);

        $question = Question::create([
            'unit_id' => $validatedData['unit_id'],
            'sub_unit_id' => $validatedData['sub_unit_id'],
            'text' => $validatedData['text'],
            'hint' => $validatedData['hint'] ?? null,
            'type' => $validatedData['type'],
        ]);

        $question->answers()->createMany($validatedData['answers']);

        if(isset($validatedData['image'])) {
            uploadFileOnMedia($validatedData['image'], $question, MediaCollection::QUESTION_COLLECTION);
        }
    }

    public function update($validatedData, $id)
    {
        $this->chackeQuestionCorrectAnswersCount($validatedData);

        $question = Question::findByIdOrFail($id);

        $this->contextService->checkIfQuestionBelongsToQuizBeforeDeletingOrUpdating($question);

        $question->update($validatedData);

        $question->answers()->delete();

        $question->answers()->createMany($validatedData['answers']);
    }

    public function destroy($id)
    {
        $question = Question::findByIdOrFail($id);
        
        $this->contextService->checkIfQuestionBelongsToQuizBeforeDeletingOrUpdating($question);

        $question->delete();
    }

    private function chackeQuestionCorrectAnswersCount($validatedData)
    {
        if($validatedData['type'] == QuestionTypeEnum::ONE_SELECT->value) {
            $correctAnswers = collect($validatedData['answers'])->where('is_correct', true)->count();
            if($correctAnswers != 1) {
                return unprocessableFailure([], ExceptionMessages::MSG_QUESTION_ANSWERS_IS_CORRECT_ONLY_ONE);
            }
        }
        else {
            $correctAnswers = collect($validatedData['answers'])->where('is_correct', true)->count();
           
            if($correctAnswers <= 1) {
                return unprocessableFailure([], ExceptionMessages::MSG_QUESTION_ANSWERS_IS_CORRECT_MORE_THAN_ONE);
            }
        }
    }
}
