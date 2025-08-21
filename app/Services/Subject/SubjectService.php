<?php

namespace App\Services\Subject;

use App\Constants\MediaCollection;
use App\Models\Subject;
use App\Services\Base\ContextService;
use Illuminate\Support\Facades\DB;

class SubjectService
{
    public function __construct(
        protected ContextService $contextService
    ) {}

    /**
     * Get all Subjects with optional filtering
     */
    public function getAll($data)
    {
        $query = Subject::orderBy('created_at', 'desc')
                ->with([ 'course', 'units', 'responsibilities']);

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
        $subject = Subject::create($data);

        if (isset($data['image'])) {
            uploadFileOnMedia($data['image'], $subject, MediaCollection::SUBJECT_COLLECTION);
        }

        $subject->save();

        // Update parent Course numbers
        $this->contextService->updateParentNumberOfContents($subject, '+');
    }

    public function update($data, $id)
    {
        $subject = Subject::findByIdOrFail($id);

        $subject->update($data);

        $subject->save();
    }

    public function destroy($id)
    {
        $subject = Subject::findByIdOrFail($id);
        
        // Update parent Course numbers before deletion
        $this->contextService->updateParentNumberOfContents($subject, '-');
        
        $subject->delete();
    }

    public function changePublishStatus($id)
    {
        $subject = Subject::findByIdOrFail($id);

        $this->contextService->changePublishStatus($subject , 'content');
    }

    public function changeAccessTypeStatus($id)
    {
        $subject = Subject::findByIdOrFail($id);
        
        $this->contextService->changeContentAccessTypeStatus($subject);
    }
}
