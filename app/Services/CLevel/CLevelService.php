<?php

namespace App\Services\CLevel;

use App\Constants\MediaCollection;
use App\Models\CLevel;
use App\Services\Base\ContextService;
use Illuminate\Support\Facades\DB;

class CLevelService
{
    public function __construct(
        protected ContextService $contextService
    ) {}

    /**
     * Get all CLevels with optional ELevel filtering
     */
    public function getAll($data)
    {
        $query = CLevel::orderBy('created_at', 'desc')
                ->with(['eLevel', 'courses', 'responsibilities']);

        // Filter by ELevel ID if provided
        if (isset($data['e_level_id'])) {
            $query->where('e_level_id', $data['e_level_id']);
        }

        return getOrPaginate($query, $data);
    }

    public function store($data)
    {
        $cLevel = CLevel::create($data);

        if (isset($data['image'])) {
            uploadFileOnMedia($data['image'], $cLevel, MediaCollection::C_LEVEL_COLLECTION);
        }

        $cLevel->save();

        // Update parent ELevel numbers
        $this->contextService->updateParentNumberOfContents($cLevel, '+');
    }

    public function update($data, $id)
    {
        $cLevel = CLevel::findByIdOrFail($id);

        $cLevel->update($data);

        $cLevel->save();
    }

    public function destroy($id)
    {
        $cLevel = CLevel::findByIdOrFail($id);
        
        // Update parent ELevel numbers before deletion
        $this->contextService->updateParentNumberOfContents($cLevel, '-');
        
        $cLevel->delete();
    }

    public function changePublishStatus($id)
    {
        $cLevel = CLevel::findByIdOrFail($id);

        $this->contextService->changePublishStatus($cLevel , 'content');
    }

}
