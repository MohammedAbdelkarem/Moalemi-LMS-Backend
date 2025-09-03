<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Unit;
use App\Models\User;
use App\Models\Story;
use App\Models\Banner;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\SubUnit;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\Story\StoryResource;
use App\Http\Resources\Banner\BannerResource;
use App\Http\Resources\Course\CourseResource;
use App\Http\Resources\Subject\SubjectResource;
use App\Http\Resources\Teacher\TeacherResource;

/**
 * Class StudentHomeService.
 */
class StudentHomeService
{
    public function get()
    {
        $profile = User::findByIdOrFail(auth()->id() , ['c_level' , 'e_level']);

        $stories = Story::active()
                ->cLevel()
                ->where('storiable_id', $profile->c_level_id)
                ->get();

        $banners = Banner::active()
                ->cLevel()
                ->where('bannerable_id', $profile->c_level_id)
                ->get();

        $courses = Course::published()->where('c_level_id', $profile->c_level_id)->get();

        $subjects = Subject::published()->where('c_level_id', $profile->c_level_id)->get();

        $latestLessons = [];

        $teachers = User::where('role_id', 3)->whereHas('responsibilities', function($query) use ($profile){
            $query->where('c_level_id', $profile->c_level_id);
        })->get();

        $leaderBoard = [];

        $quizzes = [];
                

        return [
            'profile' => UserResource::make($profile),
            'stories' => StoryResource::collection($stories),
            'banners' => BannerResource::collection($banners),
            'courses' => CourseResource::collection($courses),
            'subjects' => SubjectResource::collection($subjects),
            'latestLessons' => $latestLessons,
            'teachers' => TeacherResource::collection($teachers),
            'leaderBoard' => $leaderBoard,
            'quizzes' => $quizzes,
        ];
    }
}
