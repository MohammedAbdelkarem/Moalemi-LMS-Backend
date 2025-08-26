<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\CLevel;
use App\Models\Course;
use App\Models\ELevel;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\SubUnit;
use Illuminate\Database\Seeder;
use App\Enums\PublishStatusEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eLevels = ELevel::create([
            'name' => 'elevel 1',
            'bio' => 'elevel 1',
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'number_of_contents' => 1,
            'number_of_published_contents' => 1,
        ]);

        $cLevels = CLevel::create([
            'name' => 'clevel 1',
            'bio' => 'clevel 1',
            'e_level_id' => $eLevels->id,
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'number_of_contents' => 1,
            'number_of_published_contents' => 1,
        ]);

        $course = Course::create([
            'name' => 'course 1',
            'bio' => 'course 1',
            'c_level_id' => $cLevels->id,
            'e_level_id' => $eLevels->id,
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'number_of_contents' => 1,
            'number_of_published_contents' => 1,
        ]);

        $subject = Subject::create([
            'name' => 'subject 1',
            'bio' => 'subject 1',
            'course_id' => $course->id,
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'e_level_id' => $eLevels->id,
            'c_level_id' => $cLevels->id,
            'number_of_contents' => 1,
            'number_of_published_contents' => 1,
        ]);
        
        $unit = Unit::create([
            'name' => 'unit 1',
            'bio' => 'unit 1',
            'subject_id' => $subject->id,
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'e_level_id' => $eLevels->id,
            'c_level_id' => $cLevels->id,
            'course_id' => $course->id,
            'number_of_contents' => 1,
            'number_of_published_contents' => 1,
        ]);

        $subunit = SubUnit::create([
            'name' => 'subunit 1',
            'bio' => 'subunit 1',
            'unit_id' => $unit->id,
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'e_level_id' => $eLevels->id,
            'c_level_id' => $cLevels->id,
            'course_id' => $course->id,
            'subject_id' => $subject->id,
            'number_of_contents' => 1,
            'number_of_published_contents' => 1,
        ]);
        
        $lesson = Lesson::create([
            'name' => 'lesson 1',
            'bio' => 'lesson 1',
            'sub_unit_id' => $subunit->id,
            'publish_status' => PublishStatusEnum::PUBLISHED->value,
            'e_level_id' => $eLevels->id,
            'c_level_id' => $cLevels->id,
            'course_id' => $course->id,
            'subject_id' => $subject->id,
            'unit_id' => $unit->id,
        ]);
    }
}
