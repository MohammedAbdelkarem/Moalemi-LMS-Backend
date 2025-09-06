<?php

namespace App\Services\Purchase;

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
}
