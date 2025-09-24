<?php

namespace App\Services\Progress;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\QuizResult;
use App\Constants\MediaCollection;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Media\MediaResource;

/**
 * Class ProgressService.
 */
class ProgressService
{
    public function updateStudyMinutes($minutes)
    {
        $user = User::findByIdOrFail(auth()->id());
        $user->study_minutes += $minutes;
        $user->save();
    }
    private function numberOfWatchedLessonsInSubject($student_id, $subject_id)
    {
        return Lesson::published()
            ->where('subject_id', $subject_id)
            ->whereHas('viewers', function ($query) use ($student_id) {
                $query->where('student_id', $student_id);
            })
            ->count();
    }
    private function numberOfAllLessonsInSubject($student_id, $subject_id)
    {
        $count =  Lesson::published()
            ->where('subject_id', $subject_id)
            ->count();

        return $count > 0 ? $count : 1;
    }
    private function numberOfAllWatchedLessons($student_id)
    {
        return Lesson::published()
            ->whereHas('unlockedContexts', function ($query) use ($student_id) {
                $query->where('user_id', $student_id);
            })
            ->whereHas('viewers', function ($query) use ($student_id) {
                $query->where('student_id', $student_id);
            })
            ->count();
    }
    private function numberOfAllLessons($student_id)
    {
        $count = Lesson::published()
            ->whereHas('unlockedContexts', function ($query) use ($student_id) {
                $query->where('user_id', $student_id);
            })
            ->count();

        return $count > 0 ? $count : 1;
    }
    private function quizzesResult($student_id)
    {
        $quizResults = QuizResult::where('student_id', $student_id)->get();

        $quizzes = Quiz::whereIn('id', $quizResults->pluck('quiz_id')->toArray())->get();

        $resultsSum = $quizResults->sum('degree');

        $quizzesSum = $quizzes->sum('degree');

        $quizzesSum = $quizzesSum > 0 ? $quizzesSum : 1;

        return $resultsSum / $quizzesSum * 100;
    }

    public function adminProgress($studentId)
    {
        $profile = User::findByIdOrFail($studentId , ['c_level' , 'e_level']);

        $progress = $this->numberOfAllWatchedLessons($studentId) / $this->numberOfAllLessons($studentId) * 100;

        $studyHours = $profile->study_minutes / 60;

        $quizzesResult = $this->quizzesResult($studentId);

        $unlockedSubjects = Subject::whereHas('unlockedContexts', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })->get();

        $subjectProgress = [];

        foreach ($unlockedSubjects as $subject) {
            $subjectProgress[$subject->name] = $this->numberOfWatchedLessonsInSubject($studentId, $subject->id) / $this->numberOfAllLessonsInSubject($studentId, $subject->id) * 100;
        }

        return [
            'profile' => UserResource::make($profile),
            'progress' => $progress,
            'studyHours' => $studyHours,
            'quizzesResult' => $quizzesResult,
            'subjectProgress' => $subjectProgress,
        ];
    }
    public function progress($studentId)
    {
        $profile = User::findByIdOrFail($studentId , ['c_level' , 'e_level']);

        $progress = $this->numberOfAllWatchedLessons($studentId) / $this->numberOfAllLessons($studentId) * 100;

        $studyHours = $profile->study_minutes / 60;

        $quizzesResult = $this->quizzesResult($studentId);

        $unlockedSubjects = Subject::whereHas('unlockedContexts', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })->get();

        $subjectProgress = [];

        foreach ($unlockedSubjects as $subject) {
            $subjectProgress[$subject->name] = [
                $this->numberOfWatchedLessonsInSubject($studentId, $subject->id)
                 / $this->numberOfAllLessonsInSubject($studentId, $subject->id) 
                 * 100,
                 MediaResource::make($subject->getFirstMedia(MediaCollection::SUBJECT_COLLECTION)),
                 $subject->course->name,
            ];
        }

        return [
            'profile' => UserResource::make($profile),
            'progress' => $progress,
            'studyHours' => $studyHours,
            'quizzesResult' => $quizzesResult,
            'subjectProgress' => $subjectProgress,
        ];
    }
}
