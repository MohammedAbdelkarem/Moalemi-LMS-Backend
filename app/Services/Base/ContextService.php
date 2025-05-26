<?php

namespace App\Services\Base;

use App\Enums\PublishStatusEnum;
use App\Constants\ExceptionMessages;

/**
 * Class ContextService.
 */
class ContextService
{
    public function changePublishStatus($context)
    {
        $context->publish_status = 
        ($context->publish_status == PublishStatusEnum::PUBLISHED)
         ? PublishStatusEnum::DRAFT 
         : PublishStatusEnum::PUBLISHED;

        $context->save();
    }

    public function checkIfPlanIsPublished($plan)
    {
        if($plan->publish_status == PublishStatusEnum::DRAFT)
            return unprocessableFailure([] , ExceptionMessages::MSG_CAN_NOT_SUBSCRIBE_TO_DRAFT_PLAN);
    }
}
