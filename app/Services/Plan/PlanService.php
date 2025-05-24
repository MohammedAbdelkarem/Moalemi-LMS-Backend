<?php

namespace App\Services\Plan;

use App\Models\Plan;
use App\Constants\ExceptionMessages;
use App\Services\Base\ContextService;

/**
 * Class PlanService.
 */
class PlanService
{

    protected $contextService;
    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }
    public function getAll($data)
    {
        return getOrPaginate(
            Plan::with('doctors'),
            $data
        );
    }

    public function show($id)
    {
        return plan::findByIdOrFail($id  , ['doctors']);
    }

    public function store($data)
    {
        $item = Plan::create($data);

        $item->save();
    }

    public function update($data , $id)
    {
        $item =  Plan::findByIdOrFail($id);

        $item->update($data);

        $item->save();
    }

    public function destroy($id)
    {
        $item = Plan::findByIdOrFail($id);

        if($item->doctors()->count() > 0)
            return forbiddenFailure(null , ExceptionMessages::MSG_CAN_NOT_DELETE_PLAN_CUZ_HAS_SUBSCRIPED_DOCTORS);

        $item->delete();
    }

    public function changePublishStatus($id)
    {
        $item = Plan::findByIdOrFail($id);

        $this->contextService->changePublishStatus($item);
    }
}
