<?php

namespace App\Services\Base;

use App\Enums\PublishStatusEnum;

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
}
