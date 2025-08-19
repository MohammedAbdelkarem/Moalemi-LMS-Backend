<?php

namespace App\Services\Lesson;

use App\Constants\MediaCollection;
use App\Models\Lesson;
use App\Services\Base\ContextService;
use Illuminate\Support\Facades\DB;

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
        $query = Lesson::orderBy('created_at', 'desc')
                ->with([ 'subUnit', 'quizzes', 'files', 'responsibilities']);

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
        if (isset($data['video'])) {
            // Upload video first
            $media = uploadFileOnMedia($data['video'], $lesson, MediaCollection::LESSON_VIDEO_COLLECTION, true);
            
            // Get duration from Media Library and convert to minutes
            if ($media) {
                $durationInSeconds = $media->getCustomProperty('duration');
                if ($durationInSeconds && is_numeric($durationInSeconds)) {
                    $durationInMinutes = (int) ceil($durationInSeconds / 60);
                    
                    // Update lesson duration and add to all parent levels
                    $this->contextService->updateLessonDurationAndParentLevels($lesson, $durationInMinutes , '+');
                }
            }
        }

        $lesson->save();

        // Update parent SubUnit numbers
        $this->contextService->updateParentNumberOfContents($lesson, '+');
    }

    public function update($data, $id)
    {
        $lesson = Lesson::findByIdOrFail($id);

        $lesson->update($data);

        $lesson->save();
    }

    public function destroy($id)
    {
        $lesson = Lesson::findByIdOrFail($id);
        
        // Update parent SubUnit numbers before deletion
        $this->contextService->updateParentNumberOfContents($lesson, '-');
        
        $lesson->delete();
    }

    public function changePublishStatus($id)
    {
        $lesson = Lesson::findByIdOrFail($id);

        $this->contextService->changeContentPublishStatus($lesson);
    }
}
