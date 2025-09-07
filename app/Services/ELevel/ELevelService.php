<?php

namespace App\Services\ELevel;

use App\Constants\MediaCollection;
use App\Models\ELevel;
use App\Services\Base\ContextService;
use Illuminate\Support\Facades\DB;

class ELevelService
{
    public function __construct(
        protected ContextService $contextService
    ) {}

    public function getAll($data)
    {
        return getOrPaginate(
            ELevel::orderBy('created_at', 'desc' , 'responsibilities')
                    ->with(['cLevels' , 'responsibilities']),
            $data
        );
    }

    public function getList()
    {
        return ELevel::published()->get();
    }

    public function show($id)
    {
        return ELevel::findByIdOrFail($id, ['cLevels' , 'responsibilities']);
    }

    public function store($data)
    {
        $eLevel = ELevel::create($data);

        if (isset($data['image'])) {
            uploadFileOnMedia($data['image'], $eLevel, MediaCollection::E_LEVEL_COLLECTION);
        }

        $eLevel->save();
    }

    public function update($data, $id)
    {
        $eLevel = ELevel::findByIdOrFail($id);

        $this->contextService->checkIfDraftBeforeDeletingOrUpdating($id , ELevel::class);

        $eLevel->update($data);

        $eLevel->save();
    }

    public function destroy($id)
    {
        $eLevel = ELevel::findByIdOrFail($id);

        $this->contextService->checkIfDraftBeforeDeletingOrUpdating($id , ELevel::class);
        $this->contextService->checkIfHasRegisterdStudentsBeforeDeleting($id , ELevel::class);
        $this->contextService->checkIfHasContentBeforeDeleting($id , ELevel::class);
        
        $eLevel->delete();
    }

    public function changePublishStatus($id)
    {
        $eLevel = ELevel::findByIdOrFail($id);

        $this->contextService->checkIfContextHasContentBeforePublish($id , ELevel::class);
        $this->contextService->checkIfContextHasResponsibilitiesBeforePublish($id , ELevel::class);

        $this->contextService->changePublishStatus($eLevel , 'content');
    }
}
