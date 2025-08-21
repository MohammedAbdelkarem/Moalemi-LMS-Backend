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
            ELevel::orderBy('created_at', 'desc')
                    ->with(['cLevels', 'responsibilities']),
            $data
        );
    }

    public function show($id)
    {
        return ELevel::findByIdOrFail($id, ['cLevels', 'responsibilities']);
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

        $eLevel->update($data);

        $eLevel->save();
    }

    public function destroy($id)
    {
        $eLevel = ELevel::findByIdOrFail($id);
        
        $eLevel->delete();
    }

    public function changePublishStatus($id)
    {
        $eLevel = ELevel::findByIdOrFail($id);

        $this->contextService->changePublishStatus($eLevel , 'content');
    }
}
