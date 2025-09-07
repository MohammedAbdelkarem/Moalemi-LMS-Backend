<?php

namespace App\Services\Purchase;

use App\Constants\ExceptionMessages;
use App\Models\Course;
use App\Models\Subject;

/**
 * Class PurchaseService.
 */
class PurchaseService
{
    public function unlockContexts($context_id , $model)
    {
        $context = $model::findByIdOrFail($context_id);

        $this->checkIfPurchasedChildsExists($context_id , $model);

        $unlockedContext = $context->unlockedContexts()->create([
            'user_id' => auth()->id(),
        ]);
        $this->incrementPurchasedStudents($context);

        if($model == Course::class)
        {
            $subjects = $context->publishedSubjects()->get();
            foreach($subjects as $subject)
            {
                $subject->unlockedContexts()->create([
                    'user_id' => auth()->id(),
                ]);

                $units = $subject->publishedUnits()->get();
                foreach($units as $unit)
                {
                    $unit->unlockedContexts()->create([
                        'user_id' => auth()->id(),
                    ]);

                    $subUnits = $unit->publishedSubUnits()->get();
                    foreach($subUnits as $subUnit)
                    {
                        $subUnit->unlockedContexts()->create([
                            'user_id' => auth()->id(),
                        ]);

                        $lessons = $subUnit->publishedLessons()->get();
                        foreach($lessons as $lesson)
                        {
                            $lesson->unlockedContexts()->create([
                                'user_id' => auth()->id(),
                            ]);
                        }
                    }
                }
            }
        }
        elseif($model == Subject::class)
        {
            $units = $context->publishedUnits()->get();
            foreach($units as $unit)
            {
                $unit->unlockedContexts()->create([
                    'user_id' => auth()->id(),
                ]);

                $subUnits = $unit->publishedSubUnits()->get();
                foreach($subUnits as $subUnit)
                {
                    $subUnit->unlockedContexts()->create([
                        'user_id' => auth()->id(),
                    ]);

                    $lessons = $subUnit->publishedLessons()->get();
                    foreach($lessons as $lesson)
                    {
                        $lesson->unlockedContexts()->create([
                            'user_id' => auth()->id(),
                        ]);
                    }
                }
            }
        }
        else 
        {
            $subUnits = $context->publishedSubUnits()->get();
            foreach($subUnits as $subUnit)
            {
                $subUnit->unlockedContexts()->create([
                    'user_id' => auth()->id(),
                ]);

                $lessons = $subUnit->publishedLessons()->get();
                foreach($lessons as $lesson)
                {
                    $lesson->unlockedContexts()->create([
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

        return $unlockedContext;
    }
    private function incrementPurchasedStudents($context)
    {
        $context->increment('number_of_purchased_students');
    }

    private function checkIfPurchasedChildsExists($context_id , $model)
    {
        $context = $model::findByIdOrFail($context_id);

        if($model == Course::class)
        {
            $publishedSubjects = $context->publishedSubjects()->get();
            foreach($publishedSubjects as $subject)
            {
                if($subject->unlockedContexts()->where('user_id', auth()->id())->exists())
                {
                    return forbiddenFailure([] , ExceptionMessages::MSG_ENTITY_HAS_SUB_ENTITIES_PURCHASED);
                }
            }

            $publishedUnits = $context->publishedUnits()->get();
            foreach($publishedUnits as $unit)
            {
                if($unit->unlockedContexts()->where('user_id', auth()->id())->exists())
                {
                    return forbiddenFailure([] , ExceptionMessages::MSG_ENTITY_HAS_SUB_ENTITIES_PURCHASED);
                }
            }
        }
        else if($model == Subject::class)
        {
            $publishedUnits = $context->publishedUnits()->get();
            foreach($publishedUnits as $unit)
            {
                if($unit->unlockedContexts()->where('user_id', auth()->id())->exists())
                {
                    return forbiddenFailure([] , ExceptionMessages::MSG_ENTITY_HAS_SUB_ENTITIES_PURCHASED);
                }
            }
        }
    }
}
