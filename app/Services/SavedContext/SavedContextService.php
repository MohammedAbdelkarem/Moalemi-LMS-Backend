<?php

namespace App\Services\SavedContext;

use App\Models\Lesson;
use App\Models\Question;
use App\Models\SavedContext;

/**
 * Class SavedContextService.
 */
class SavedContextService
{
    public function toggle($data)
    {
        $model = getModel($data['context_type']);

        $context = $model::findByIdOrFail($data['context_id']);

        $alreadySaved = $context->savedByStudents()->where('student_id', auth()->id())->exists();

        if($alreadySaved)
            $context->savedByStudents()->where('student_id', auth()->id())->delete();
        else
            $context->savedByStudents()->create(['student_id' => auth()->id()]);
    }

    public function getSavedContexts($data , $student_id , $model)
    {
        $records = $model::whereHas('savedByStudents', function($query) use ($student_id) {
            $query->where('student_id', $student_id);
        });

        return getOrPaginate($records, $data);
    }
}
