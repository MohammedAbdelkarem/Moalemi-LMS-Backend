<?php

namespace App\Services\File;

use App\Models\File;
use App\Models\Subject;
use App\Constants\Resources;
use App\Constants\MediaCollection;
use App\Services\Base\ContextService;

class FileService
{
    public function __construct(
        protected ContextService $contextService,
    ) {}

    public function getAll($data = [])
    {
        $query = File::query();

        // Filter by context ID if provided
        if (isset($data['context_id'])) {
            $query->where('context_id', $data['context_id']);
        }

        // Filter by context type if provided
        if (isset($data['context_type'])) {
            $data['context_type'] = getModel($data['context_type']);
            
            $query->where('context_type', $data['context_type']);
        }

        return getOrPaginate(
            $query->orderBy('priority', 'asc'),
            $data
        );
    }

    public function store($data)
    {
        $data['context_type'] = getModel($data['context_type']);

        $file = File::create($data);
        
        if(isset($data['file']))
            uploadFileOnMedia($data['file'] , $file , MediaCollection::FILE_COLLECTION);

        $this->contextService->updateParentNumberOfFiles($file , $file->context_id , '+');

        $file->save();
    }

    public function update($data, $id)
    {
        $file = File::findByIdOrFail($id);

        $file->update($data);
    }

    public function destroy($id)
    {
        $file = File::findByIdOrFail($id);

        $this->contextService->updateParentNumberOfFiles($file , $file->context_id , '-');

        $file->delete();
    }

    public function changePublishStatus($id)
    {
        $file = File::findByIdOrFail($id);

        $this->contextService->changePublishStatus($file , 'file');
    }

    public function changePriority($contextsData)
    {
        $this->contextService->changeContextsPriority($contextsData, File::class);
    }

    public function getPurchasedFiles($student_id , $data , $model)
    {
        return getOrPaginate(
            File::published()
            ->whereHas('context', function($query) use ($student_id, $model) {
                $query->whereHas('unlockedContexts', function($query) use ($student_id, $model) {
                    $query->where('user_id', $student_id)
                    ->where('context_type', $model);
                });
            }),
            $data
        );
    }
}
