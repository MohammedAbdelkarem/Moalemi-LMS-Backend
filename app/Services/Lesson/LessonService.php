<?php

namespace App\Services\Lesson;

use App\Models\Lesson;
use App\Enums\PublishStatusEnum;
use App\Constants\MediaCollection;
use Illuminate\Support\Facades\DB;
use App\Services\Base\ContextService;

class LessonService
{
    public function __construct(
        protected ContextService $contextService
    ) {}

    /**
     * Get all Lessons with optional filtering
     */
    public function getAll($data)
    {
        $query = Lesson::orderBy('priority', 'asc')
                ->with([ 'subUnit', 'quizzes', 'files' , 'responsibilities']);

        // Filter by SubUnit ID if provided
        if (isset($data['sub_unit_id'])) {
            $query->where('sub_unit_id', $data['sub_unit_id']);
        }

        // Filter by Unit ID if provided
        if (isset($data['unit_id'])) {
            $query->where('unit_id', $data['unit_id']);
        }

        // Filter by Subject ID if provided
        if (isset($data['subject_id'])) {
            $query->where('subject_id', $data['subject_id']);
        }

        // Filter by Course ID if provided
        if (isset($data['course_id'])) {
            $query->where('course_id', $data['course_id']);
        }

        // Filter by CLevel ID if provided
        if (isset($data['c_level_id'])) {
            $query->where('c_level_id', $data['c_level_id']);
        }

        // Filter by ELevel ID if provided
        if (isset($data['e_level_id'])) {
            $query->where('e_level_id', $data['e_level_id']);
        }

        return getOrPaginate($query, $data);
    }

    public function store($data)
    {
        $lesson = Lesson::create($data);

        // Handle multiple images
        if (isset($data['images'])) {
            uploadFilesOnMedia($data['images'], $lesson, MediaCollection::LESSON_COLLECTION);
        }

        // Handle single video and calculate duration
        if (isset($data['videos'])) {
            // Upload video first
            uploadFilesOnMedia($data['videos'], $lesson, MediaCollection::LESSON_VIDEO_COLLECTION);
            
            $this->contextService->updateLessonDurationAndParentLevels($lesson, $lesson->duration , '+');
        }

        $lesson->save();

        // Update parent SubUnit numbers
        $this->contextService->updateParentNumberOfContents($lesson, '+');
    }

    public function uploadVideos($data, $id)
    {
        $lesson = Lesson::findByIdOrFail($id);

        $file['image'] = $data['video'];
        $file['quality'] = $data['quality'];

        uploadFileOnMedia($file, $lesson, MediaCollection::LESSON_VIDEO_COLLECTION);

        $lesson->save();
    }

    public function update($data, $id)
    {
        $lesson = Lesson::findByIdOrFail($id);

        $this->contextService->checkIfDraftBeforeDeletingOrUpdating($id , Lesson::class);

        $lesson->update($data);

        $lesson->save();
    }

    public function destroy($id)
    {
        $lesson = Lesson::findByIdOrFail($id);
        
        $this->contextService->checkIfDraftBeforeDeletingOrUpdating($id , Lesson::class);
        $this->contextService->checkIfHasPurchasedStudentsBeforeDeleting($id , Lesson::class);

        // Update parent SubUnit numbers before deletion
        $this->contextService->updateParentNumberOfContents($lesson, '-');

        // Update lesson duration and subtract from all parent levels
        $this->contextService->updateLessonDurationAndParentLevels($lesson, $lesson->duration , '-');

        $lesson->delete();
    }

    public function changePublishStatus($id, $status)
    {
        $lesson = Lesson::findByIdOrFail($id);

        if($status == PublishStatusEnum::PUBLISHED->value) {
            $this->contextService->checkIfParentPublishedBeforePublish($id , Lesson::class);
        }

        $this->contextService->changeWithChildsPublishStatus($id , Lesson::class , $status);
    }

    public function changePriority($contextsData)
    {
        $this->contextService->changeContextsPriority($contextsData , Lesson::class);
    }

    public function recordLessonView($lesson_id, $student_id)
    {
        $lesson = Lesson::findByIdOrFail($lesson_id);
        
        // Check if the student has already watched this lesson
        $existingRecord = $lesson->viewers()->where('student_id', $student_id)->first();
        
        if (!$existingRecord) {
            // Record the lesson view with current timestamp
            $lesson->viewers()->attach($student_id, [
                'watched_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
