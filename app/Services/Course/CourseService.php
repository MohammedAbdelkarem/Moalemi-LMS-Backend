<?php

namespace App\Services\Course;

use App\Constants\MediaCollection;
use App\Models\Course;
use App\Services\Base\ContextService;
use Illuminate\Support\Facades\DB;

class CourseService
{
    public function __construct(
        protected ContextService $contextService
    ) {}

    /**
     * Get all Courses with optional CLevel filtering
     */
    public function getAll($data)
    {
        $query = Course::orderBy('created_at', 'desc')
                ->with(['cLevel', 'subjects' , 'responsibilities']);

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
        $course = Course::create($data);

        if (isset($data['image'])) {
            uploadFileOnMedia($data['image'], $course, MediaCollection::COURSE_COLLECTION);
        }

        $course->save();

        // Update parent CLevel numbers
        $this->contextService->updateParentNumberOfContents($course, '+');
    }

    public function update($data, $id)
    {
        $course = Course::findByIdOrFail($id);

        $this->contextService->checkIfDraftBeforeDeletingOrUpdating($id , Course::class);

        $course->update($data);

        $course->save();
    }

    public function destroy($id)
    {
        $course = Course::findByIdOrFail($id);

        $this->contextService->checkIfDraftBeforeDeletingOrUpdating($id , Course::class);
        $this->contextService->checkIfHasPurchasedStudentsBeforeDeleting($id , Course::class);
        $this->contextService->checkIfHasContentBeforeDeleting($id , Course::class);
        
        // Update parent CLevel numbers before deletion
        $this->contextService->updateParentNumberOfContents($course, '-');
        
        $course->delete();
    }

    public function changePublishStatus($id)
    {
        $course = Course::findByIdOrFail($id);

        $this->contextService->checkIfParentPublishedBeforePublish($id , Course::class);
        $this->contextService->checkIfContextHasContentBeforePublish($id , Course::class);
        $this->contextService->checkIfContextHasResponsibilitiesBeforePublish($id , Course::class);

        $this->contextService->changePublishStatus($course , 'content');
    }

    public function changeAccessTypeStatus($id)
    {
        $course = Course::findByIdOrFail($id);
        
        $this->contextService->changeContentAccessTypeStatus($course);
    }
}
